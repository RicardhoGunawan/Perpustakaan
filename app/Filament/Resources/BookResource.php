<?php
// app/Filament/Resources/BookResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Buku'),
                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255)
                            ->label('Penulis'),
                        Forms\Components\TextInput::make('isbn')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignorable: fn ($record) => $record)
                            ->label('ISBN'),
                        Forms\Components\TextInput::make('publisher')
                            ->required()
                            ->maxLength(255)
                            ->label('Penerbit'),
                        Forms\Components\TextInput::make('publication_year')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y'))
                            ->label('Tahun Terbit'),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Stok'),
                        Forms\Components\Select::make('category')
                            ->options([
                                'Fiksi' => 'Fiksi',
                                'Non-Fiksi' => 'Non-Fiksi',
                                'Referensi' => 'Referensi',
                                'Sains' => 'Sains',
                                'Teknologi' => 'Teknologi',
                                'Matematika' => 'Matematika',
                                'Bahasa' => 'Bahasa',
                                'Sejarah' => 'Sejarah',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required()
                            ->label('Kategori'),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->label('Deskripsi'),
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('book-covers')
                            ->label('Cover Buku'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->defaultImageUrl(url('/images/default-book-cover.png'))
                    ->square()
                    ->label('Cover'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->label('Judul'),
                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->sortable()
                    ->label('Penulis'),
                Tables\Columns\TextColumn::make('publisher')
                    ->searchable()
                    ->limit(20)
                    ->label('Penerbit'),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->label('Stok'),
                Tables\Columns\TextColumn::make('availableStock')
                    ->label('Tersedia')
                    ->numeric()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->leftJoin('book_loans', 'books.id', '=', 'book_loans.book_id')
                            ->selectRaw('books.*, (books.stock - COALESCE(SUM(CASE WHEN book_loans.status = "dipinjam" THEN book_loans.quantity ELSE 0 END), 0)) as available')
                            ->groupBy('books.id')
                            ->orderBy('available', $direction);
                    }),
                Tables\Columns\TextColumn::make('isbn')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->label('ISBN'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Fiksi' => 'Fiksi',
                        'Non-Fiksi' => 'Non-Fiksi',
                        'Referensi' => 'Referensi',
                        'Sains' => 'Sains',
                        'Teknologi' => 'Teknologi',
                        'Matematika' => 'Matematika',
                        'Bahasa' => 'Bahasa',
                        'Sejarah' => 'Sejarah',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->label('Kategori'),
                Tables\Filters\Filter::make('availableOnly')
                    ->query(fn (Builder $query) => $query->whereRaw('stock > COALESCE((SELECT SUM(quantity) FROM book_loans WHERE book_id = books.id AND status = "dipinjam"), 0)'))
                    ->label('Hanya Tersedia'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }    
}