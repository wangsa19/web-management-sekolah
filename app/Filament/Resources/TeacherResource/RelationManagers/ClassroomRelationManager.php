<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Periode;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Classroom;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'classroom';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable()
                    ->label('Select Class')
                    ->relationship(name: 'classroom', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug'),

                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Add Class Room')
                            ->modalButton('Add Class Room')
                            ->modalWidth('2xl');
                    }),
                Select::make('periode_id')
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->searchable()
                    ->label('Select Periode')
                    ->relationship(name: 'periode', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Add Periode')
                            ->modalButton('Add Periode')
                            ->modalWidth('2xl');
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('periode.name'),
                ToggleColumn::make('is_open'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
