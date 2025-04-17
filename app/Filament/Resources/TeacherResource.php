<?php
// app/Filament/Resources/TeacherResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->description('Detail akun untuk login guru.')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Pengguna')
                            ->dehydrated(fn ($state, $livewire) => $livewire instanceof Pages\CreateTeacher)
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateTeacher),

                        Forms\Components\TextInput::make('user.email')
                            ->email()
                            ->required()
                            ->unique(table: User::class, column: 'email', ignorable: fn ($record) => $record?->user)
                            ->label('Email')
                            ->dehydrated(fn ($state, $livewire) => $livewire instanceof Pages\CreateTeacher)
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateTeacher),

                        Forms\Components\TextInput::make('user.password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn ($state, $livewire): bool => 
                                $state && $livewire instanceof Pages\CreateTeacher)
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateTeacher)
                            ->label('Password'),
                    ]),

                Forms\Components\Section::make('Informasi Guru')
                    ->description('Detail informasi biodata guru.')
                    ->schema([
                        Forms\Components\TextInput::make('nip')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignorable: fn ($record) => $record)
                            ->label('NIP'),

                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),

                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->label('Mata Pelajaran'),

                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255)
                            ->label('Nomor Telepon'),

                        Forms\Components\Textarea::make('address')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Alamat'),

                        Forms\Components\FileUpload::make('profile_photo')
                            ->image()
                            ->directory('teacher-photos')
                            ->label('Foto Profil'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name) . '&color=7F9CF5&background=EBF4FF')
                    ->circular()
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable()
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('user.email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->label('Nomor Telepon'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->options(function () {
                        return Teacher::distinct()->pluck('subject', 'subject')->toArray();
                    })
                    ->label('Mata Pelajaran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('assignRole')
                    ->label('Tetapkan Sebagai Guru')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->visible(function ($record) {
                        return !$record->user->hasRole('guru');
                    })
                    ->action(function ($record) {
                        $record->user->assignRole('guru');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('assignTeacherRole')
                        ->label('Tetapkan Semua Sebagai Guru')
                        ->icon('heroicon-o-key')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if (!$record->user->hasRole('guru')) {
                                    $record->user->assignRole('guru');
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user']);
    }
}