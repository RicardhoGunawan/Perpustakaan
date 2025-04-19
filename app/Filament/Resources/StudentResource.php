<?php
// app/Filament/Resources/StudentResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->description('Detail akun untuk login siswa.')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Pengguna')
                            ->dehydrated(fn($state, $livewire) => $livewire instanceof Pages\CreateStudent)
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateStudent),

                        Forms\Components\TextInput::make('user.email')
                            ->email()
                            ->required()
                            ->unique(table: User::class, column: 'email', ignorable: fn($record) => $record?->user)
                            ->label('Email')
                            ->dehydrated(fn($state, $livewire) => $livewire instanceof Pages\CreateStudent)
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateStudent),

                        Forms\Components\TextInput::make('user.password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? \Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateStudent)
                            ->label('Password'),
                    ]),

                Forms\Components\Section::make('Informasi Siswa')
                    ->description('Detail informasi biodata siswa.')
                    ->schema([
                        Forms\Components\TextInput::make('nis')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignorable: fn($record) => $record)
                            ->label('NIS'),

                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),

                        Forms\Components\Select::make('class')
                            ->options([
                                'X-1' => 'X-1',
                                'X-2' => 'X-2',
                                'X-3' => 'X-3',
                                'XI-IPA-1' => 'XI-IPA-1',
                                'XI-IPA-2' => 'XI-IPA-2',
                                'XI-IPS-1' => 'XI-IPS-1',
                                'XI-IPS-2' => 'XI-IPS-2',
                                'XII-IPA-1' => 'XII-IPA-1',
                                'XII-IPA-2' => 'XII-IPA-2',
                                'XII-IPS-1' => 'XII-IPS-1',
                                'XII-IPS-2' => 'XII-IPS-2',
                            ])
                            ->required()
                            ->label('Kelas'),

                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255)
                            ->label('Nomor Telepon'),

                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir'),

                        Forms\Components\Textarea::make('address')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Alamat'),

                        Forms\Components\FileUpload::make('profile_photo')
                            ->image()
                            ->directory('student-photos')
                            ->label('Foto Profil'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->full_name) . '&color=7F9CF5&background=EBF4FF')
                    ->circular()
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('nis')
                    ->searchable()
                    ->sortable()
                    ->label('NIS'),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('class')
                    ->searchable()
                    ->sortable()
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('user.email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->label('Nomor Telepon')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.member.member_number')
                    ->label('No. ID')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->placeholder('Tidak ada'),
                Tables\Columns\IconColumn::make('user.member.is_active')
                    ->boolean()
                    ->label('Status Anggota')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class')
                    ->options([
                        'X-1' => 'X-1',
                        'X-2' => 'X-2',
                        'X-3' => 'X-3',
                        'XI-IPA-1' => 'XI-IPA-1',
                        'XI-IPA-2' => 'XI-IPA-2',
                        'XI-IPS-1' => 'XI-IPS-1',
                        'XI-IPS-2' => 'XI-IPS-2',
                        'XII-IPA-1' => 'XII-IPA-1',
                        'XII-IPA-2' => 'XII-IPA-2',
                        'XII-IPS-1' => 'XII-IPS-1',
                        'XII-IPS-2' => 'XII-IPS-2',
                    ])
                    ->label('Kelas'),
                Tables\Filters\Filter::make('has_membership')
                    ->query(fn(Builder $query): Builder => $query->whereHas('user.member'))
                    ->label('Memiliki Keanggotaan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('viewCard')
                    ->label('Lihat Kartu')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn($record) => $record->user->member)
                    ->url(fn($record) => route('member.card.show', $record->user->member->id))
                    ->openUrlInNewTab(),
                Action::make('createMembership')
                    ->label('Buat Kartu Anggota')
                    ->icon('heroicon-o-identification')
                    ->color('success')
                    ->visible(function ($record) {
                        return !$record->user->member;
                    })
                    ->form([
                        Forms\Components\DatePicker::make('valid_until')
                            ->required()
                            ->default(now()->addYear())
                            ->label('Berlaku Hingga'),
                    ])
                    ->action(function (array $data, $record) {
                        // Generate unique member number
                        $memberNumber = 'SIS-' . $record->nis . '-' . Str::random(5);

                        // Create membership for student
                        $record->user->member()->create([
                            'member_number' => $memberNumber,
                            'valid_until' => $data['valid_until'],
                            'is_active' => true,
                        ]);

                        // Assign siswa role if not already assigned
                        if (!$record->user->hasRole('siswa')) {
                            $record->user->assignRole('siswa');
                        }
                    }),
                Action::make('printMemberCard')
                    ->label('Cetak Kartu')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(function ($record) {
                        return $record->user->member;
                    })
                    ->action(function ($record) {
                        $student = $record;
                        $member = $record->user->member;

                        $pdf = PDF::loadView('pdf.member-card', [
                            'student' => $student,
                            'member' => $member
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $student->full_name . '_kartu_anggota.pdf');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('printMemberCards')
                        ->label('Cetak Kartu Anggota')
                        ->icon('heroicon-o-printer')
                        ->action(function ($records) {
                            $pdf = PDF::loadView('pdf.member-cards', [
                                'students' => $records->filter(function ($record) {
                                    return $record->user->member;
                                })
                            ]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'kartu_anggota_batch.pdf');
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'user.member']);

    }
}