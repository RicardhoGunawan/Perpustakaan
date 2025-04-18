<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BookLoansRelationManager extends RelationManager
{
    protected static string $relationship = 'bookLoans';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peminjaman')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),

                        Forms\Components\Hidden::make('isTeacher')
                            ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->hasRole('guru')),

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
                                'dipinjam' => 'Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'terlambat' => 'Terlambat',
                            ])
                            ->default('dipinjam')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Buku')
                    ->sortable()
                    ->description(fn (Model $record) => $record->book->author),

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
                        'primary' => 'dipinjam',
                        'success' => 'dikembalikan',
                        'danger' => 'terlambat',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('return')
                    ->label('Kembalikan Buku')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Model $record) => $record->status === 'dipinjam')
                    ->action(function (Model $record) {
                        $record->update([
                            'return_date' => now(),
                            'status' => 'dikembalikan',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}
