<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\Pages\ListStudents;
use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nis')
                            ->label('NIS'),
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Student'),
                        Select::make('gender')
                            ->options([
                                'Male' => 'Laki-laki',
                                'Female' => 'Perempuan'
                            ]),
                        DatePicker::make('birthday')
                            ->label('Birthday'),
                        Select::make('religion')
                            ->options([
                                'Islam' => "Islam",
                                'Katolik' => "Katolik",
                                'Protestan' => "Protestan",
                                'Hindu' => "Hindu",
                                'Buddha' => "Buddha",
                                'Khonghucu' => "Khonghucu",
                            ]),
                        TextInput::make('contact'),
                        FileUpload::make('profile')
                            ->directory('students'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        $recordsPerPage = (int) $livewire->getTableRecordsPerPage();
                        $currentPage = (int) $livewire->getTablePage();

                        return (string) (
                            $rowLoop->iteration +
                            ($recordsPerPage * ($currentPage - 1))
                        );
                    }
                ),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Student'),
                TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('birthday')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn(string $state): string => ucwords("{$state}")),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'accept' => 'accept',
                        'off' => 'off',
                        'move' => 'move',
                        'grade' => 'grade',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Change Status')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('Status')
                                ->label('Status')
                                ->options([
                                    'accept' => 'Accept',
                                    'off' => 'Off',
                                    'move' => 'Move',
                                    'grade' => 'Grade',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (Student $record) use ($data) {
                                Student::where('id', $record->id)->update([
                                    'status' => $data['Status'],
                                ]);
                            });
                        }),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();

        if ($locale == 'id') {
            return 'Murid';
        } else {
            return 'Students';
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('nis'),
                TextEntry::make('name'),
            ]);
    }
}
