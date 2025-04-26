<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BookLoanResource\Pages;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BookLoanResource extends Resource
{
    protected static ?string $model = BookLoan::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'Manajemen Perpustakaan';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peminjaman')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Peminjam')
                            ->options(function () {
                                $users = User::whereHas('roles', function ($query) {
                                    $query->whereIn('name', ['siswa', 'guru']);
                                })->get();

                                $options = [];
                                foreach ($users as $user) {
                                    $role = $user->roles()->first();
                                    $detail = '';

                                    if ($role->name === 'siswa' && $user->student) {
                                        $detail = "[Siswa] {$user->student->full_name} - {$user->student->class}";
                                    } elseif ($role->name === 'guru' && $user->teacher) {
                                        $detail = "[Guru] {$user->teacher->full_name} - {$user->teacher->subject}";
                                    } else {
                                        continue;
                                    }

                                    $options[$user->id] = $detail;
                                }

                                return $options;
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $user = User::find($state);
                                if ($user && $user->hasRole('guru')) {
                                    $set('isTeacher', true);
                                } else {
                                    $set('isTeacher', false);
                                    $set('borrowed_for', null);
                                    $set('quantity', 1);
                                }
                            })
                            ->default(function (callable $set) {
                                $currentUser = auth()->user();

                                // Set isTeacher jika user adalah guru
                                if ($currentUser && $currentUser->hasRole('guru')) {
                                    $set('isTeacher', true);
                                }

                                return $currentUser->id;
                            })
                            ->disabled(function () {
                                return !auth()->user()->hasRole('super_admin');
                            }),

                        Forms\Components\Hidden::make('isTeacher')
                            ->default(function () {
                                $currentUser = auth()->user();
                                return $currentUser && $currentUser->hasRole('guru');
                            }),

                        Forms\Components\Select::make('book_id')
                            ->label('Buku')
                            ->options(function () {
                                return Book::where('stock', '>', 0)
                                    ->get()
                                    ->mapWithKeys(function ($book) {
                                        $available = $book->availableStock;
                                        return [$book->id => "{$book->title} oleh {$book->author} (Tersedia: {$available})"];
                                    });
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->visible(function (callable $get) {
                                return $get('isTeacher');
                            }),

                        Forms\Components\TextInput::make('borrowed_for')
                            ->label('Untuk Kelas')
                            ->required()
                            ->visible(function (callable $get) {
                                return $get('isTeacher');
                            }),

                        Forms\Components\DatePicker::make('loan_date')
                            ->label('Tanggal Pinjam')
                            ->default(now())
                            ->required(),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Tanggal Kembali')
                            ->default(now()->addDays(7))
                            ->required(),

                        Forms\Components\DatePicker::make('return_date')
                            ->label('Tanggal Dikembalikan')
                            ->nullable(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'menunggu_persetujuan' => 'Menunggu Persetujuan',
                                'ditolak' => 'Ditolak',
                                'dipinjam' => 'Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'terlambat' => 'Terlambat',
                            ])
                            ->default('menunggu_persetujuan')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->helperText('Catatan ini akan ditampilkan kepada peminjam')
                            ->columnSpanFull()
                            ->visible(fn (string $context): bool => auth()->user()->hasRole(['super_admin', 'admin']))
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->sortable()
                    ->description(function (Model $record) {
                        if ($record->user->hasRole('siswa') && $record->user->student) {
                            return "Siswa - {$record->user->student->class}";
                        } elseif ($record->user->hasRole('guru') && $record->user->teacher) {
                            return "Guru - {$record->user->teacher->subject}";
                        }
                        return '';
                    }),

                Tables\Columns\TextColumn::make('book.title')
                    ->label('Buku')
                    ->searchable()
                    ->sortable()
                    ->description(fn(BookLoan $record) => $record->book->author),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrowed_for')
                    ->label('Untuk Kelas')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Tanggal Kembali')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Tanggal Dikembalikan')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu_persetujuan',
                        'danger' => ['ditolak', 'terlambat'],
                        'primary' => 'dipinjam',
                        'success' => 'dikembalikan',
                    ]),
                    
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Disetujui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu_persetujuan' => 'Menunggu Persetujuan',
                        'ditolak' => 'Ditolak',
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat' => 'Terlambat',
                    ])
                    ->label('Status'),

                Tables\Filters\Filter::make('loan_date')
                    ->form([
                        Forms\Components\DatePicker::make('loan_date_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('loan_date_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['loan_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('loan_date', '>=', $date),
                            )
                            ->when(
                                $data['loan_date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('loan_date', '<=', $date),
                            );
                    })
                    ->label('Tanggal Pinjam'),

                Tables\Filters\SelectFilter::make('user_role')
                    ->options([
                        'siswa' => 'Siswa',
                        'guru' => 'Guru',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('user.roles', function ($query) use ($data) {
                            $query->where('name', $data['value']);
                        });
                    })
                    ->label('Tipe Peminjam'),
                    
                Tables\Filters\SelectFilter::make('approval')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Telah Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        
                        if ($data['value'] === 'pending') {
                            return $query->where('status', 'menunggu_persetujuan');
                        } elseif ($data['value'] === 'approved') {
                            return $query->where('status', 'dipinjam')->whereNotNull('approved_at');
                        } elseif ($data['value'] === 'rejected') {
                            return $query->where('status', 'ditolak');
                        }
                        
                        return $query;
                    })
                    ->label('Status Persetujuan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                // Approve book loan action (for admins only)
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(BookLoan $record) => 
                        $record->status === 'menunggu_persetujuan' && 
                        auth()->user()->hasRole(['super_admin', 'admin']))
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->placeholder('Catatan untuk peminjam (opsional)')
                    ])
                    ->action(function (BookLoan $record, array $data) {
                        $record->update([
                            'status' => 'dipinjam',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                            'admin_notes' => $data['admin_notes'] ?? null,
                        ]);
                    }),
                    
                // Reject book loan action (for admins only)
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn(BookLoan $record) => 
                        $record->status === 'menunggu_persetujuan' && 
                        auth()->user()->hasRole(['super_admin', 'admin']))
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Alasan Penolakan')
                            ->placeholder('Berikan alasan penolakan peminjaman buku')
                            ->required()
                    ])
                    ->action(function (BookLoan $record, array $data) {
                        $record->update([
                            'status' => 'ditolak',
                            'admin_notes' => $data['admin_notes'],
                        ]);
                    }),
                
                Tables\Actions\Action::make('return')
                    ->label('Kembalikan Buku')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(BookLoan $record) => $record->status === 'dipinjam')
                    ->action(function (BookLoan $record) {
                        $record->update([
                            'return_date' => now(),
                            'status' => 'dikembalikan',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approveSelected')
                        ->label('Setujui Peminjaman Terpilih')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'menunggu_persetujuan') {
                                    $record->update([
                                        'status' => 'dipinjam',
                                        'approved_at' => now(),
                                        'approved_by' => auth()->id(),
                                    ]);
                                }
                            }
                        })
                        ->visible(fn() => auth()->user()->hasRole(['super_admin', 'admin'])),
                    Tables\Actions\BulkAction::make('returnSelected')
                        ->label('Kembalikan Buku Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'dipinjam') {
                                    $record->update([
                                        'return_date' => now(),
                                        'status' => 'dikembalikan',
                                    ]);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookLoans::route('/'),
            'create' => Pages\CreateBookLoan::route('/create'),
            'edit' => Pages\EditBookLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['user', 'book', 'user.student', 'user.teacher', 'user.roles']);
            
        // Non-admin users only see their own loans
        if (!auth()->user()->hasRole(['super_admin', 'admin'])) {
            $query->where('user_id', auth()->id());
        }
        
        return $query;
    }
}