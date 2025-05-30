<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuenteResource\Pages;
use App\Filament\Resources\FuenteResource\RelationManagers;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use App\Models\Fuente;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FuenteResource extends Resource
{
    protected static ?string $model = Fuente::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $label = 'Fuentes';
    protected static ?string $pluralLabel = 'Fuentes';
    protected static ?string $navigationGroup = 'Catálogos';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de la Fuente')
                    ->description('Complete los datos de la fuente de información.')
                    ->schema([
                        TextInput::make('fuente')
                            ->label('Nombre de la Fuente')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('pais')->label('País')->required()->options([
                            'AF' => 'Afghanistan 🇦🇫',
                            'AL' => 'Albania 🇦🇱',
                            'DZ' => 'Algeria 🇩🇿',
                            'AS' => 'American Samoa 🇦🇸',
                            'AD' => 'Andorra 🇦🇩',
                            'AO' => 'Angola 🇦🇴',
                            'AI' => 'Anguilla 🇦🇮',
                            'AQ' => 'Antarctica 🇦🇶',
                            'AR' => 'Argentina 🇦🇷',
                            'AM' => 'Armenia 🇦🇲',
                            'AW' => 'Aruba 🇦🇼',
                            'AU' => 'Australia 🇦🇺',
                            'AT' => 'Austria 🇦🇹',
                            'AZ' => 'Azerbaijan 🇦🇿',
                            'BS' => 'Bahamas 🇧🇸',
                            'BH' => 'Bahrain 🇧🇭',
                            'BD' => 'Bangladesh 🇧🇩',
                            'BB' => 'Barbados 🇧🇧',
                            'BY' => 'Belarus 🇧🇾',
                            'BE' => 'Belgium 🇧🇪',
                            'BZ' => 'Belize 🇧🇿',
                            'BJ' => 'Benin 🇧🇯',
                            'BM' => 'Bermuda 🇧🇲',
                            'BT' => 'Bhutan 🇧🇹',
                            'BO' => 'Bolivia 🇧🇴',
                            'BQ' => 'Bonaire, Sint Eustatius and Saba 🇧🇶',
                            'BA' => 'Bosnia and Herzegovina 🇧🇦',
                            'BW' => 'Botswana 🇧🇼',
                            'BR' => 'Brazil 🇧🇷',
                            'IO' => 'British Indian Ocean Territory 🇮🇴',
                            'VG' => 'British Virgin Islands 🇻🇬',
                            'BN' => 'Brunei 🇧🇳',
                            'BG' => 'Bulgaria 🇧🇬',
                            'BF' => 'Burkina Faso 🇧🇫',
                            'BI' => 'Burundi 🇧🇮',
                            'CV' => 'Cabo Verde 🇨🇻',
                            'KH' => 'Cambodia 🇰🇭',
                            'CM' => 'Cameroon 🇨🇲',
                            'CA' => 'Canada 🇨🇦',
                            'KY' => 'Cayman Islands 🇰🇾',
                            'CF' => 'Central African Republic 🇨🇫',
                            'TD' => 'Chad 🇹🇩',
                            'CL' => 'Chile 🇨🇱',
                            'CN' => 'China 🇨🇳',
                            'CX' => 'Christmas Island 🇨🇽',
                            'CC' => 'Cocos (Keeling) Islands 🇨🇨',
                            'CO' => 'Colombia 🇨🇴',
                            'KM' => 'Comoros 🇰🇲',
                            'CG' => 'Congo 🇨🇬',
                            'CD' => 'Congo (DRC) 🇨🇩',
                            'CK' => 'Cook Islands 🇨🇰',
                            'CR' => 'Costa Rica 🇨🇷',
                            'CI' => 'Côte d\'Ivoire 🇨🇮',
                            'HR' => 'Croatia 🇭🇷',
                            'CU' => 'Cuba 🇨🇺',
                            'CW' => 'Curaçao 🇨🇼',
                            'CY' => 'Cyprus 🇨🇾',
                            'CZ' => 'Czech Republic 🇨🇿',
                            'DK' => 'Denmark 🇩🇰',
                            'DJ' => 'Djibouti 🇩🇯',
                            'DM' => 'Dominica 🇩🇲',
                            'DO' => 'Dominican Republic 🇩🇴',
                            'EC' => 'Ecuador 🇪🇨',
                            'EG' => 'Egypt 🇪🇬',
                            'SV' => 'El Salvador 🇸🇻',
                            'GQ' => 'Equatorial Guinea 🇬🇶',
                            'ER' => 'Eritrea 🇪🇷',
                            'EE' => 'Estonia 🇪🇪',
                            'SZ' => 'Eswatini 🇸🇿',
                            'ET' => 'Ethiopia 🇪🇹',
                            'FK' => 'Falkland Islands 🇫🇰',
                            'FO' => 'Faroe Islands 🇫🇴',
                            'FJ' => 'Fiji 🇫🇯',
                            'FI' => 'Finland 🇫🇮',
                            'FR' => 'France 🇫🇷',
                            'GF' => 'French Guiana 🇬🇫',
                            'PF' => 'French Polynesia 🇵🇫',
                            'TF' => 'French Southern Territories 🇹🇫',
                            'GA' => 'Gabon 🇬🇦',
                            'GM' => 'Gambia 🇬🇲',
                            'GE' => 'Georgia 🇬🇪',
                            'DE' => 'Germany 🇩🇪',
                            'GH' => 'Ghana 🇬🇭',
                            'GI' => 'Gibraltar 🇬🇮',
                            'GR' => 'Greece 🇬🇷',
                            'GL' => 'Greenland 🇬🇱',
                            'GD' => 'Grenada 🇬🇩',
                            'GP' => 'Guadeloupe 🇬🇵',
                            'GU' => 'Guam 🇬🇺',
                            'GT' => 'Guatemala 🇬🇹',
                            'GG' => 'Guernsey 🇬🇬',
                            'GN' => 'Guinea 🇬🇳',
                            'GW' => 'Guinea-Bissau 🇬🇼',
                            'GY' => 'Guyana 🇬🇾',
                            'HT' => 'Haiti 🇭🇹',
                            'HN' => 'Honduras 🇭🇳',
                            'HK' => 'Hong Kong 🇭🇰',
                            'HU' => 'Hungary 🇭🇺',
                            'IS' => 'Iceland 🇮🇸',
                            'IN' => 'India 🇮🇳',
                            'ID' => 'Indonesia 🇮🇩',
                            'IR' => 'Iran 🇮🇷',
                            'IQ' => 'Iraq 🇮🇶',
                            'IE' => 'Ireland 🇮🇪',
                            'IM' => 'Isle of Man 🇮🇲',
                            'IL' => 'Israel 🇮🇱',
                            'IT' => 'Italy 🇮🇹',
                            'JM' => 'Jamaica 🇯🇲',
                            'JP' => 'Japan 🇯🇵',
                            'JE' => 'Jersey 🇯🇪',
                            'JO' => 'Jordan 🇯🇴',
                            'KZ' => 'Kazakhstan 🇰🇿',
                            'KE' => 'Kenya 🇰🇪',
                            'KI' => 'Kiribati 🇰🇮',
                            'KW' => 'Kuwait 🇰🇼',
                            'KG' => 'Kyrgyzstan 🇰🇬',
                            'LA' => 'Laos 🇱🇦',
                            'LV' => 'Latvia 🇱🇻',
                            'LB' => 'Lebanon 🇱🇧',
                            'LS' => 'Lesotho 🇱🇸',
                            'LR' => 'Liberia 🇱🇷',
                            'LY' => 'Libya 🇱🇾',
                            'LI' => 'Liechtenstein 🇱🇮',
                            'LT' => 'Lithuania 🇱🇹',
                            'LU' => 'Luxembourg 🇱🇺',
                            'MO' => 'Macau 🇲🇴',
                            'MG' => 'Madagascar 🇲🇬',
                            'MW' => 'Malawi 🇲🇼',
                            'MY' => 'Malaysia 🇲🇾',
                            'MV' => 'Maldives 🇲🇻',
                            'ML' => 'Mali 🇲🇱',
                            'MT' => 'Malta 🇲🇹',
                            'MH' => 'Marshall Islands 🇲🇭',
                            'MQ' => 'Martinique 🇲🇶',
                            'MR' => 'Mauritania 🇲🇷',
                            'MU' => 'Mauritius 🇲🇺',
                            'MX' => 'Mexico 🇲🇽',
                            'FM' => 'Micronesia 🇫🇲',
                            'MD' => 'Moldova 🇲🇩',
                            'MC' => 'Monaco 🇲🇨',
                            'MN' => 'Mongolia 🇲🇳',
                            'ME' => 'Montenegro 🇲🇪',
                            'MS' => 'Montserrat 🇲🇸',
                            'MA' => 'Morocco 🇲🇦',
                            'MZ' => 'Mozambique 🇲🇿',
                            'MM' => 'Myanmar (Burma) 🇲🇲',
                            'NA' => 'Namibia 🇳🇦',
                            'NR' => 'Nauru 🇳🇷',
                            'NP' => 'Nepal 🇳🇵',
                            'NL' => 'Netherlands 🇳🇱',
                            'NC' => 'New Caledonia 🇳🇨',
                            'NZ' => 'New Zealand 🇳🇿',
                            'NI' => 'Nicaragua 🇳🇮',
                            'NE' => 'Niger 🇳🇪',
                            'NG' => 'Nigeria 🇳🇬',
                            'NU' => 'Niue 🇳🇺',
                            'NF' => 'Norfolk Island 🇳🇫',
                            'KP' => 'North Korea 🇰🇵',
                            'MK' => 'North Macedonia 🇲🇰',
                            'MP' => 'Northern Mariana Islands 🇲🇵',
                            'NO' => 'Norway 🇳🇴',
                            'OM' => 'Oman 🇴🇲',
                            'PK' => 'Pakistan 🇵🇰',
                            'PW' => 'Palau 🇵🇼',
                            'PS' => 'Palestine 🇵🇸',
                            'PA' => 'Panama 🇵🇦',
                            'PG' => 'Papua New Guinea 🇵🇬',
                            'PY' => 'Paraguay 🇵🇾',
                            'PE' => 'Peru 🇵🇪',
                            'PH' => 'Philippines 🇵🇭',
                            'PN' => 'Pitcairn Islands 🇵🇳',
                            'PL' => 'Poland 🇵🇱',
                            'PT' => 'Portugal 🇵🇹',
                            'PR' => 'Puerto Rico 🇵🇷',
                            'QA' => 'Qatar 🇶🇦',
                            'RE' => 'Réunion 🇷🇪',
                            'RO' => 'Romania 🇷🇴',
                            'RU' => 'Russia 🇷🇺',
                            'RW' => 'Rwanda 🇷🇼',
                            'WS' => 'Samoa 🇼🇸',
                            'SM' => 'San Marino 🇸🇲',
                            'ST' => 'Sao Tome and Principe 🇸🇹',
                            'SA' => 'Saudi Arabia 🇸🇦',
                            'SN' => 'Senegal 🇸🇳',
                            'RS' => 'Serbia 🇷🇸',
                            'SC' => 'Seychelles 🇸🇨',
                            'SL' => 'Sierra Leone 🇸🇱',
                            'SG' => 'Singapore 🇸🇬',
                            'SX' => 'Sint Maarten 🇸🇽',
                            'SK' => 'Slovakia 🇸🇰',
                            'SI' => 'Slovenia 🇸🇮',
                            'SB' => 'Solomon Islands 🇸🇧',
                            'SO' => 'Somalia 🇸🇴',
                            'ZA' => 'South Africa 🇿🇦',
                            'GS' => 'South Georgia and South Sandwich Islands 🇬🇸',
                            'KR' => 'South Korea 🇰🇷',
                            'SS' => 'South Sudan 🇸🇸',
                            'ES' => 'Spain 🇪🇸',
                            'LK' => 'Sri Lanka 🇱🇰',
                            'SD' => 'Sudan 🇸🇩',
                            'SR' => 'Suriname 🇸🇷',
                            'SJ' => 'Svalbard and Jan Mayen 🇸🇯',
                            'SE' => 'Sweden 🇸🇪',
                            'CH' => 'Switzerland 🇨🇭',
                            'SY' => 'Syria 🇸🇾',
                            'TW' => 'Taiwan 🇹🇼',
                            'TJ' => 'Tajikistan 🇹🇯',
                            'TZ' => 'Tanzania 🇹🇿',
                            'TH' => 'Thailand 🇹🇭',
                            'TL' => 'Timor-Leste 🇹🇱',
                            'TG' => 'Togo 🇹🇬',
                            'TK' => 'Tokelau 🇹🇰',
                            'TO' => 'Tonga 🇹🇴',
                            'TT' => 'Trinidad and Tobago 🇹🇹',
                            'TN' => 'Tunisia 🇹🇳',
                            'TR' => 'Turkey 🇹🇷',
                            'TM' => 'Turkmenistan 🇹🇲',
                            'TC' => 'Turks and Caicos Islands 🇹🇨',
                            'TV' => 'Tuvalu 🇹🇻',
                            'UG' => 'Uganda 🇺🇬',
                            'UA' => 'Ukraine 🇺🇦',
                            'AE' => 'United Arab Emirates 🇦🇪',
                            'GB' => 'United Kingdom 🇬🇧',
                            'US' => 'United States 🇺🇸',
                            'UY' => 'Uruguay 🇺🇾',
                            'UZ' => 'Uzbekistan 🇺🇿',
                            'VU' => 'Vanuatu 🇻🇺',
                            'VA' => 'Vatican City 🇻🇦',
                            'VE' => 'Venezuela 🇻🇪',
                            'VN' => 'Vietnam 🇻🇳',
                            'VI' => 'U.S. Virgin Islands 🇻🇮',
                            'WF' => 'Wallis and Futuna 🇼🇫',
                            'EH' => 'Western Sahara 🇪🇭',
                            'YE' => 'Yemen 🇾🇪',
                            'ZM' => 'Zambia 🇿🇲',
                            'ZW' => 'Zimbabwe 🇿🇼',
                        ])
                            ->searchable()
                            ->native(false),

                        DatePicker::make('año')
                            ->label('Año de Publicación')
                            ->required()
                            ->format('Y')
                            ->native(false)
                            ->maxDate(now()),

                        TextInput::make('url')
                            ->label('URL (opcional)')
                            ->url()
                            ->columnSpanFull() // Make url field take the full width
                            ->maxLength(255)
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fuente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('año'),
                Tables\Columns\TextColumn::make('url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListFuentes::route('/'),
            'create' => Pages\CreateFuente::route('/create'),
            'edit' => Pages\EditFuente::route('/{record}/edit'),
        ];
    }
}
