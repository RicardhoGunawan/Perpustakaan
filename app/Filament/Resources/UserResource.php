<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Profil Tambahan')
                    ->description('Isi sesuai dengan role pengguna')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipe Pengguna')
                            ->options([
                                'student' => 'Siswa',
                                'teacher' => 'Guru',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('student.full_name', null);
                                $set('teacher.full_name', null);
                            }),

                        Forms\Components\Fieldset::make('Data Siswa')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'student')
                            ->relationship('student')
                            ->mutateRelationshipDataBeforeFillUsing(function (array $data, User $record): array {
                                $data['full_name'] = $data['full_name'] ?? $record->name;
                                return $data;
                            })
                            ->schema([
                                Forms\Components\TextInput::make('nis')
                                    ->required()
                                    ->default('NIS-SEMENTARA') // Tambahkan default value
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('full_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->dehydrated(true) // Pastikan selalu disimpan meski tidak diubah
                                    ->default(fn($operation, $record) => $record?->name), // Default value dari name user

                                Forms\Components\TextInput::make('class')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('date_of_birth'),

                                Forms\Components\FileUpload::make('profile_photo')
                                    ->image()
                                    ->directory('student-profiles'),
                            ])->columns(2),


                        Forms\Components\Fieldset::make('Data Guru')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'teacher')
                            ->relationship('teacher')
                            ->mutateRelationshipDataBeforeFillUsing(function (array $data, User $record): array {
                                $data['full_name'] = $data['full_name'] ?? $record->name;
                                return $data;
                            })
                            ->schema([
                                Forms\Components\TextInput::make('nip')
                                    ->required()
                                    ->default('NIP-SEMENTARA') // Tambahkan default value
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('full_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->dehydrated(true) // Pastikan selalu disimpan meski tidak diubah
                                    ->default(fn($operation, $record) => $record?->name), // Default value dari name user

                                Forms\Components\TextInput::make('subject')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255),

                                Forms\Components\FileUpload::make('profile_photo')
                                    ->image()
                                    ->directory('teacher-profiles'),
                            ])->columns(2),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('student.profile_photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->badge(),

                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Nama Lengkap')
                    ->toggleable()
                    ->getStateUsing(fn($record) => $record->student?->full_name ?? $record->teacher?->full_name),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple(),

                Tables\Filters\Filter::make('has_student')
                    ->label('Hanya Siswa')
                    ->query(fn(Builder $query): Builder => $query->whereHas('student')),

                Tables\Filters\Filter::make('has_teacher')
                    ->label('Hanya Guru')
                    ->query(fn(Builder $query): Builder => $query->whereHas('teacher')),
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
            RelationManagers\BookLoansRelationManager::class,
            RelationManagers\BookRequestsRelationManager::class,
            RelationManagers\MemberRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // ✅ Tambahkan ini untuk memuat relasi student dan teacher
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['student', 'teacher', 'roles']);
    }
}
