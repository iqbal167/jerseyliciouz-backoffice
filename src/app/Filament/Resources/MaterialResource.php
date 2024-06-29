<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Daftar Bahan Produksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Bahan')
                    ->placeholder('Misal: MILANO,WAFEL,BRAZIL STANDARD')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->placeholder('Keterangan')
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori')
                    ->placeholder('Pilih Kategori')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_textile')
                    ->label('Set Harga Untuk Bahan Konveksi')
                    ->reactive()
                    ->afterStateUpdated(
                        fn ($state, callable $set) => $state ? $set('cost_ratio', null) : $set('cost_ratio', 'hidden')
                    )
                    ->afterStateUpdated(
                        fn ($state, callable $set) => $state ? $set('cost_per_kg', null) : $set('cost_per_kg', 'hidden')
                    )
                    ->onColor('info')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cost_per_kg')
                    ->label('Harga Per Kilogram')
                    ->placeholder('Masukan Harga Per Kilogram')
                    ->required()
                    ->step(1000)
                    ->hidden(
                        fn (Get $get): bool => $get('is_textile') == false
                    )
                    ->reactive()
                    ->afterStateUpdated(
                        fn ($state, callable $set, callable $get) => $set('cost_per_unit', $state / number_format(($get('cost_ratio') ?: 1), 0, ',', ''))
                    )
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cost_ratio')
                    ->label('Rasio Konversi')
                    ->placeholder('Masukan Rasio Kg ke Satuan')
                    ->required()
                    ->rules(['gt:0'])
                    ->step(0.01)
                    ->inputMode('decimal')
                    ->hidden(
                        fn (Get $get): bool => $get('is_textile') == false
                    )
                    ->reactive()
                    ->afterStateUpdated(
                        fn ($state, callable $set, callable $get) => $set('cost_per_unit', number_format(($get('cost_per_kg') ?: 1) / $state, 0, ',', ''))
                    )
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cost_per_unit')
                    ->label('Harga Per Satuan')
                    ->placeholder('Masukan Harga Per Satuan')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->readOnly(
                        fn (Get $get): bool => $get('is_textile') == true
                    )
                    ->columnSpanFull(),
                Forms\Components\TagsInput::make('tags')
                    ->label('Tag')
                    ->splitKeys(['Tab', ''])
                    ->placeholder('Tambah Tag (Tekan Tab untuk tambah tag lainnya)')
                    ->suggestions([
                        'jersey',
                        'non-jersey',
                        'atasan',
                        'bawahan',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Bahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost_per_unit')
                    ->label('Harga Satuan')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
