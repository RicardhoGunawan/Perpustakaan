<?php
// app/Filament/Resources/SchoolClassResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\SchoolClassResource\Pages;
use App\Models\SchoolClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('grade')
                            ->options([
                                'X' => 'X',
                                'XI' => 'XI',
                                'XII' => 'XII',
                            ])
                            ->required()
                            ->label('Tingkat'),

                        Forms\Components\TextInput::make('class_name')
                            ->required()
                            ->maxLength(50)
                            ->label('Nama Kelas'),

                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->label('Status Aktif'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('grade')
                    ->searchable()
                    ->sortable()
                    ->label('Tingkat'),
                    
                Tables\Columns\TextColumn::make('class_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kelas'),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Kelas Lengkap'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Status Aktif'),
                    
                Tables\Columns\TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Jumlah Siswa'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grade')
                    ->options([
                        'X' => 'X',
                        'XI' => 'XI',
                        'XII' => 'XII',
                    ])
                    ->label('Tingkat'),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->students()->count() > 0) {
                            throw new \Exception('Kelas ini tidak dapat dihapus karena digunakan oleh beberapa siswa.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->students()->count() > 0) {
                                    throw new \Exception('Beberapa kelas tidak dapat dihapus karena digunakan oleh siswa.');
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Ubah Status Aktif')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function ($records, $data) {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => $data['is_active'] ?? true,
                                ]);
                            }
                        })
                        ->form([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->required(),
                        ]),
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
            'index' => Pages\ListSchoolClasses::route('/'),
            'create' => Pages\CreateSchoolClass::route('/create'),
            'edit' => Pages\EditSchoolClass::route('/{record}/edit'),
        ];
    }
}