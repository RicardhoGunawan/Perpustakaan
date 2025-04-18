<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'user';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
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
                    ]),

                Forms\Components\Section::make('Profil Pengguna')
                    ->schema([
                        Forms\Components\Placeholder::make('full_name')
                            ->label('Nama Lengkap')
                            ->content(fn ($record) => $record->student?->full_name ?? $record->teacher?->full_name ?? '-'),

                        Forms\Components\Placeholder::make('class_or_subject')
                            ->label('Kelas / Mata Pelajaran')
                            ->content(fn ($record) => $record->student?->class ?? $record->teacher?->subject ?? '-'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('student.profile_photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->badge(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->getStateUsing(fn ($record) => $record->student?->full_name ?? $record->teacher?->full_name),

                Tables\Columns\TextColumn::make('class_or_subject')
                    ->label('Kelas / Mata Pelajaran')
                    ->getStateUsing(fn ($record) => $record->student?->class ?? $record->teacher?->subject),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_student')
                    ->label('Hanya Siswa')
                    ->query(fn (Builder $query): Builder => $query->whereHas('student')),

                Tables\Filters\Filter::make('has_teacher')
                    ->label('Hanya Guru')
                    ->query(fn (Builder $query): Builder => $query->whereHas('teacher')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
