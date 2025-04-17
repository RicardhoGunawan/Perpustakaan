<?php
// app/Filament/Resources/BookRequestResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\BookRequestResource\Pages;
use App\Models\BookRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookRequestResource extends Resource
{
    protected static ?string $model = BookRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Perpustakaan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Request Buku')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemohon')
                            ->options(function () {
                                $users = User::whereHas('roles', function ($query) {
                                    $query->whereIn('name', ['guru']);
                                })->get();
                                
                                $options = [];
                                foreach ($users as $user) {
                                    if ($user->hasRole('guru') && $user->teacher) {
                                        $options[$user->id] = "[Guru] {$user->teacher->full_name} - {$user->teacher->subject}";
                                    }
                                }
                                
                                return $options;
                            })
                            ->searchable()
                            ->required(),
                            
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Buku')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('author')
                            ->label('Penulis')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('publisher')
                            ->label('Penerbit')
                            ->maxLength(255),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                            
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),
                            
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                            
                        Forms\Components\DatePicker::make('processed_at')
                            ->label('Tanggal Diproses')
                            ->nullable(),
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
                    
                Tables\Columns\TextColumn::make('user.teacher.full_name')
                    ->label('Pemohon')
                    ->sortable()
                    ->searchable()
                    ->description(fn (BookRequest $record) => $record->user->teacher->subject ?? ''),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                    
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Tanggal Diproses')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status'),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi jika diperlukan
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookRequests::route('/'),
            'create' => Pages\CreateBookRequest::route('/create'),
            'edit' => Pages\EditBookRequest::route('/{record}/edit'),
        ];
    }
}