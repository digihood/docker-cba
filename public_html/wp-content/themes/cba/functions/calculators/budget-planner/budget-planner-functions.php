<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function budget_planner_get_default_config() {
    $categories = array(
        array( 'id' => 'income',        'name' => 'Příjmy',                   'slug' => 'income',        'order' => 1,  'active' => true ),
        array( 'id' => 'housing',       'name' => 'Bydlení',                  'slug' => 'housing',       'order' => 2,  'active' => true ),
        array( 'id' => 'transport',     'name' => 'Doprava',                  'slug' => 'transport',     'order' => 3,  'active' => true ),
        array( 'id' => 'food',          'name' => 'Jídlo a domácnost',        'slug' => 'food',          'order' => 4,  'active' => true ),
        array( 'id' => 'entertainment', 'name' => 'Předplatné a zábava',      'slug' => 'entertainment', 'order' => 5,  'active' => true ),
        array( 'id' => 'vices',         'name' => 'Drobné neřesti',           'slug' => 'vices',         'order' => 6,  'active' => true ),
        array( 'id' => 'savings',       'name' => 'Finanční rezervy',         'slug' => 'savings',       'order' => 7,  'active' => true ),
        array( 'id' => 'digital',       'name' => 'Digitální život',          'slug' => 'digital',       'order' => 8,  'active' => true ),
        array( 'id' => 'insurance',     'name' => 'Pojištění',                'slug' => 'insurance',     'order' => 9,  'active' => true ),
        array( 'id' => 'family',        'name' => 'Děti a rodina',            'slug' => 'family',        'order' => 10, 'active' => true ),
        array( 'id' => 'other',         'name' => 'Ostatní',                  'slug' => 'other',         'order' => 11, 'active' => true ),
    );

    $items = array(
        // Příjmy
        array( 'id' => 'salary',            'name' => 'Výplata / mzda',                                    'slug' => 'salary',            'type' => 'income',           'category' => 'income',        'tooltip' => 'Váš pravidelný čistý měsíční příjem ze zaměstnání.',                                                          'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'business_income',   'name' => 'Příjem z podnikání',                                'slug' => 'business_income',   'type' => 'income',           'category' => 'income',        'tooltip' => 'Příjmy z OSVČ nebo s.r.o. po odečtení daní a odvodů.',                                                       'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'side_income',       'name' => 'Vedlejší příjmy',                                   'slug' => 'side_income',       'type' => 'income',           'category' => 'income',        'tooltip' => 'Freelance, brigáda nebo jiné nepravidelné příjmy.',                                                           'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'rental_income',     'name' => 'Pronájmy',                                          'slug' => 'rental_income',     'type' => 'income',           'category' => 'income',        'tooltip' => 'Příjmy z pronájmu nemovitostí nebo jiného majetku.',                                                         'default_value' => 0, 'order' => 4,  'active' => true ),
        array( 'id' => 'other_income',      'name' => 'Jiné příjmy',                                       'slug' => 'other_income',      'type' => 'income',           'category' => 'income',        'tooltip' => 'Sociální dávky, důchod, alimenty nebo jiné zdroje příjmů.',                                                  'default_value' => 0, 'order' => 5,  'active' => true ),
        // Bydlení
        array( 'id' => 'rent_mortgage',     'name' => 'Nájem / hypotéka',                                  'slug' => 'rent_mortgage',     'type' => 'fixed_expense',    'category' => 'housing',       'tooltip' => 'Započítejte pravidelnou měsíční platbu za bydlení.',                                                          'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'energy',            'name' => 'Energie',                                           'slug' => 'energy',            'type' => 'fixed_expense',    'category' => 'housing',       'tooltip' => 'Elektřina, plyn nebo teplo – měsíční zálohy nebo vyúčtování.',                                               'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'water',             'name' => 'Voda',                                              'slug' => 'water',             'type' => 'fixed_expense',    'category' => 'housing',       'tooltip' => 'Měsíční záloha nebo spotřeba vody.',                                                                          'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'internet',          'name' => 'Internet',                                          'slug' => 'internet',          'type' => 'fixed_expense',    'category' => 'housing',       'tooltip' => 'Pevný internet v domácnosti.',                                                                                'default_value' => 0, 'order' => 4,  'active' => true ),
        array( 'id' => 'hoa_fees',          'name' => 'Fond oprav / SVJ',                                  'slug' => 'hoa_fees',          'type' => 'fixed_expense',    'category' => 'housing',       'tooltip' => 'Příspěvek do fondu oprav nebo správcovský poplatek.',                                                        'default_value' => 0, 'order' => 5,  'active' => true ),
        array( 'id' => 'home_maintenance',  'name' => 'Údržba domácnosti',                                 'slug' => 'home_maintenance',  'type' => 'variable_expense', 'category' => 'housing',       'tooltip' => 'Opravy, čisticí prostředky, drobné vybavení.',                                                               'default_value' => 0, 'order' => 6,  'active' => true ),
        // Doprava
        array( 'id' => 'car_leasing',       'name' => 'Leasing / splátka auta',                            'slug' => 'car_leasing',       'type' => 'fixed_expense',    'category' => 'transport',     'tooltip' => 'Pravidelná měsíční splátka za leasing nebo úvěr na auto.',                                                   'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'fuel',              'name' => 'Palivo',                                            'slug' => 'fuel',              'type' => 'variable_expense', 'category' => 'transport',     'tooltip' => 'Průměrné měsíční výdaje za pohonné hmoty.',                                                                   'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'public_transport',  'name' => 'MHD / vlak / autobus',                              'slug' => 'public_transport',  'type' => 'variable_expense', 'category' => 'transport',     'tooltip' => 'Jízdenky, kupony nebo předplatné veřejné dopravy.',                                                           'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'car_service',       'name' => 'Servis auta',                                       'slug' => 'car_service',       'type' => 'variable_expense', 'category' => 'transport',     'tooltip' => 'STK, opravy, pneumatiky – rozpočítané na měsíc.',                                                            'default_value' => 0, 'order' => 4,  'active' => true ),
        array( 'id' => 'car_insurance',     'name' => 'Povinné ručení a havarijní pojištění',               'slug' => 'car_insurance',     'type' => 'fixed_expense',    'category' => 'transport',     'tooltip' => 'Roční cenu pojištění vydělte 12 pro měsíční hodnotu.',                                                        'default_value' => 0, 'order' => 5,  'active' => true ),
        // Jídlo a domácnost
        array( 'id' => 'groceries',         'name' => 'Potraviny',                                         'slug' => 'groceries',         'type' => 'variable_expense', 'category' => 'food',          'tooltip' => 'Průměrné měsíční výdaje za běžné nákupy potravin.',                                                           'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'drugstore',         'name' => 'Drogerie',                                          'slug' => 'drugstore',         'type' => 'variable_expense', 'category' => 'food',          'tooltip' => 'Kosmetika, čisticí prostředky, hygienické potřeby.',                                                         'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'restaurants',       'name' => 'Restaurace a kavárny',                              'slug' => 'restaurants',       'type' => 'variable_expense', 'category' => 'food',          'tooltip' => 'Obědy, večeře venku, káva v kavárnách.',                                                                      'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'food_delivery',     'name' => 'Rozvoz jídla',                                      'slug' => 'food_delivery',     'type' => 'variable_expense', 'category' => 'food',          'tooltip' => 'Wolt, Bolt Food, Dáme jídlo a podobné služby.',                                                               'default_value' => 0, 'order' => 4,  'active' => true ),
        // Předplatné a zábava
        array( 'id' => 'streaming',         'name' => 'Netflix / HBO / Disney+ / jiné služby',             'slug' => 'streaming',         'type' => 'variable_expense', 'category' => 'entertainment', 'tooltip' => 'Videostreamingové platformy – sečtěte všechna předplatná.',                                                   'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'music',             'name' => 'Spotify / Apple Music',                             'slug' => 'music',             'type' => 'variable_expense', 'category' => 'entertainment', 'tooltip' => 'Hudební streamingové služby.',                                                                                'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'gaming',            'name' => 'Herní služby',                                      'slug' => 'gaming',            'type' => 'variable_expense', 'category' => 'entertainment', 'tooltip' => 'PlayStation Plus, Xbox Game Pass, Nintendo Online nebo herní nákupy.',                                        'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'culture',           'name' => 'Kultura a akce',                                    'slug' => 'culture',           'type' => 'variable_expense', 'category' => 'entertainment', 'tooltip' => 'Kino, divadlo, koncerty, výstavy.',                                                                            'default_value' => 0, 'order' => 4,  'active' => true ),
        array( 'id' => 'sports_hobbies',    'name' => 'Sport a koníčky',                                   'slug' => 'sports_hobbies',    'type' => 'variable_expense', 'category' => 'entertainment', 'tooltip' => 'Členství v posilovně, sportovní vybavení, koníčky.',                                                          'default_value' => 0, 'order' => 5,  'active' => true ),
        // Drobné neřesti
        array( 'id' => 'alcohol',           'name' => 'Alkohol',                                           'slug' => 'alcohol',           'type' => 'variable_expense', 'category' => 'vices',         'tooltip' => 'Víno, pivo, lihoviny – domácí i v restauraci.',                                                               'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'tobacco',           'name' => 'Cigarety / nikotin',                                'slug' => 'tobacco',           'type' => 'variable_expense', 'category' => 'vices',         'tooltip' => 'Cigarety, e-cigarety, IQOS nebo nikotinové náplasti.',                                                        'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'snacks',            'name' => 'Sladkosti a snacky',                                'slug' => 'snacks',            'type' => 'variable_expense', 'category' => 'vices',         'tooltip' => 'Čokoláda, chipsy, sladkosti – denní drobné potěšení.',                                                        'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'impulse_buying',    'name' => 'Impulzivní nákupy',                                 'slug' => 'impulse_buying',    'type' => 'variable_expense', 'category' => 'vices',         'tooltip' => 'Menší nákupy, které nejsou nutné a často vznikají spontánně.',                                                 'default_value' => 0, 'order' => 4,  'active' => true ),
        // Finanční rezervy
        array( 'id' => 'regular_savings',   'name' => 'Pravidelné spoření',                                'slug' => 'regular_savings',   'type' => 'savings',          'category' => 'savings',       'tooltip' => 'Pravidelná částka odkládaná do spořicích produktů.',                                                          'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'investments',       'name' => 'Investice',                                         'slug' => 'investments',       'type' => 'savings',          'category' => 'savings',       'tooltip' => 'ETF, akcie, dluhopisy, kryptoměny nebo jiné investiční nástroje.',                                            'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'vacation_fund',     'name' => 'Rezerva na dovolenou',                              'slug' => 'vacation_fund',     'type' => 'savings',          'category' => 'savings',       'tooltip' => 'Odkládaná částka na dovolenou nebo cestování.',                                                               'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'emergency_fund',    'name' => 'Rezerva na větší výdaje',                           'slug' => 'emergency_fund',    'type' => 'savings',          'category' => 'savings',       'tooltip' => 'Polštář na neplánované výdaje – auto, spotřebiče, zdraví.',                                                   'default_value' => 0, 'order' => 4,  'active' => true ),
        // Digitální život
        array( 'id' => 'mobile_plan',       'name' => 'Mobilní tarif',                                    'slug' => 'mobile_plan',       'type' => 'fixed_expense',    'category' => 'digital',       'tooltip' => 'Pravidelná platba za mobilní telefonní tarif.',                                                               'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'cloud_services',    'name' => 'Cloudové služby',                                   'slug' => 'cloud_services',    'type' => 'variable_expense', 'category' => 'digital',       'tooltip' => 'iCloud, Google One, Dropbox nebo jiné úložiště.',                                                             'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'ai_tools',          'name' => 'Předplatné AI nástrojů',                            'slug' => 'ai_tools',          'type' => 'variable_expense', 'category' => 'digital',       'tooltip' => 'Například ChatGPT, Claude, Midjourney nebo jiné placené AI služby.',                                         'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'software_apps',     'name' => 'Software a aplikace',                               'slug' => 'software_apps',     'type' => 'variable_expense', 'category' => 'digital',       'tooltip' => 'Adobe, Microsoft 365, antivirus nebo jiné placené aplikace.',                                                 'default_value' => 0, 'order' => 4,  'active' => true ),
        // Pojištění
        array( 'id' => 'life_insurance',    'name' => 'Životní pojištění',                                 'slug' => 'life_insurance',    'type' => 'fixed_expense',    'category' => 'insurance',     'tooltip' => 'Pravidelná platba za životní nebo úrazové pojištění.',                                                        'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'home_insurance',    'name' => 'Pojištění domácnosti',                              'slug' => 'home_insurance',    'type' => 'fixed_expense',    'category' => 'insurance',     'tooltip' => 'Pojištění bytu nebo domu a jeho vybavení.',                                                                   'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'liability_ins',     'name' => 'Pojištění odpovědnosti',                            'slug' => 'liability_ins',     'type' => 'fixed_expense',    'category' => 'insurance',     'tooltip' => 'Pojištění odpovědnosti za škodu způsobenou třetím osobám.',                                                   'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'pet_insurance',     'name' => 'Pojištění mazlíčků',                                'slug' => 'pet_insurance',     'type' => 'fixed_expense',    'category' => 'insurance',     'tooltip' => 'Veterinární pojištění nebo pojistka pro domácí zvíře.',                                                      'default_value' => 0, 'order' => 4,  'active' => true ),
        // Děti a rodina
        array( 'id' => 'school',            'name' => 'Školka / škola',                                    'slug' => 'school',            'type' => 'fixed_expense',    'category' => 'family',        'tooltip' => 'Školné, školkovné nebo poplatky za vzdělání.',                                                                'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'extracurricular',   'name' => 'Kroužky',                                           'slug' => 'extracurricular',   'type' => 'variable_expense', 'category' => 'family',        'tooltip' => 'Sportovní, umělecké nebo jiné zájmové kroužky pro děti.',                                                     'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'kids_clothing',     'name' => 'Oblečení pro děti',                                 'slug' => 'kids_clothing',     'type' => 'variable_expense', 'category' => 'family',        'tooltip' => 'Pravidelné výdaje na oblečení a obuv pro děti.',                                                              'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'alimony',           'name' => 'Výživné',                                           'slug' => 'alimony',           'type' => 'fixed_expense',    'category' => 'family',        'tooltip' => 'Pravidelné výživné placené soudem nebo dohodou.',                                                             'default_value' => 0, 'order' => 4,  'active' => true ),
        // Ostatní
        array( 'id' => 'healthcare',        'name' => 'Léky a zdraví',                                     'slug' => 'healthcare',        'type' => 'variable_expense', 'category' => 'other',         'tooltip' => 'Léky, doplňky stravy, stomatolog nebo jiné zdravotní výdaje.',                                                'default_value' => 0, 'order' => 1,  'active' => true ),
        array( 'id' => 'clothing',          'name' => 'Oblečení',                                          'slug' => 'clothing',          'type' => 'variable_expense', 'category' => 'other',         'tooltip' => 'Oblečení, obuv a módní doplňky pro dospělé.',                                                                 'default_value' => 0, 'order' => 2,  'active' => true ),
        array( 'id' => 'gifts',             'name' => 'Dárky',                                             'slug' => 'gifts',             'type' => 'variable_expense', 'category' => 'other',         'tooltip' => 'Narozeniny, svátky, Vánoce – průměrné měsíční výdaje za dárky.',                                              'default_value' => 0, 'order' => 3,  'active' => true ),
        array( 'id' => 'unexpected',        'name' => 'Neočekávané výdaje',                                'slug' => 'unexpected',        'type' => 'variable_expense', 'category' => 'other',         'tooltip' => 'Neplánované výdaje, které se pravidelně objevují.',                                                           'default_value' => 0, 'order' => 4,  'active' => true ),
    );

    $result_messages = array(
        array( 'min' => null,  'max' => -1,   'status' => 'red',    'title' => 'Pozor, jste v mínusu',          'text' => 'Vaše výdaje převyšují příjmy. Doporučujeme projít hlavně variabilní výdaje a najít položky, které lze snížit.' ),
        array( 'min' => 0,     'max' => 4999, 'status' => 'orange', 'title' => 'Jste v plusu, ale rezerva je nízká', 'text' => 'Máte pozitivní cashflow, ale prostor pro rezervu je zatím omezený.' ),
        array( 'min' => 5000,  'max' => null, 'status' => 'green',  'title' => 'Skvělá práce',                   'text' => 'Máte dobrý základ pro tvorbu rezervy nebo investování.' ),
    );

    return array(
        'categories'      => $categories,
        'items'           => $items,
        'result_messages' => $result_messages,
    );
}

function budget_planner_get_config( $post_id ) {
    $default = budget_planner_get_default_config();

    $categories_raw = get_post_meta( $post_id, '_budget_planner_categories', true );
    $items_raw      = get_post_meta( $post_id, '_budget_planner_items', true );
    $messages_raw   = get_post_meta( $post_id, '_budget_planner_result_messages', true );

    $categories = ! empty( $categories_raw ) ? json_decode( $categories_raw, true ) : null;
    $items      = ! empty( $items_raw )      ? json_decode( $items_raw, true )      : null;
    $messages   = ! empty( $messages_raw )   ? json_decode( $messages_raw, true )   : null;

    return array(
        'categories'      => ( is_array( $categories ) && ! empty( $categories ) ) ? $categories : $default['categories'],
        'items'           => ( is_array( $items ) && ! empty( $items ) )           ? $items      : $default['items'],
        'result_messages' => ( is_array( $messages ) && ! empty( $messages ) )     ? $messages   : $default['result_messages'],
    );
}

function budget_planner_get_categories( $post_id ) {
    $config = budget_planner_get_config( $post_id );
    return $config['categories'];
}

function budget_planner_get_items( $post_id ) {
    $config = budget_planner_get_config( $post_id );
    return $config['items'];
}

function budget_planner_get_result_messages( $post_id ) {
    $config = budget_planner_get_config( $post_id );
    return $config['result_messages'];
}

function budget_planner_get_saved_user_data( $post_id, $user_id ) {
    if ( ! $user_id ) {
        return array();
    }
    $data = get_user_meta( $user_id, '_budget_planner_data', true );
    if ( empty( $data ) ) {
        return array();
    }
    $decoded = json_decode( $data, true );
    if ( ! is_array( $decoded ) ) {
        return array();
    }
    if ( isset( $decoded['calculator_id'] ) && (int) $decoded['calculator_id'] !== (int) $post_id ) {
        return array();
    }
    return isset( $decoded['values'] ) ? $decoded['values'] : array();
}

function budget_planner_sanitize_money_value( $value ) {
    if ( $value === '' || $value === null ) {
        return 0;
    }
    $cleaned = preg_replace( '/[^0-9.,\-]/', '', (string) $value );
    $cleaned = str_replace( ',', '.', $cleaned );
    if ( ! is_numeric( $cleaned ) ) {
        return 0;
    }
    return max( 0, (float) $cleaned );
}

function budget_planner_calculate_totals( $values, $items ) {
    $total_income   = 0;
    $total_fixed    = 0;
    $total_variable = 0;
    $total_savings  = 0;

    foreach ( $items as $item ) {
        if ( empty( $item['active'] ) ) {
            continue;
        }
        $slug  = $item['slug'];
        $value = isset( $values[ $slug ] ) ? budget_planner_sanitize_money_value( $values[ $slug ] ) : 0;

        switch ( $item['type'] ) {
            case 'income':
                $total_income += $value;
                break;
            case 'fixed_expense':
                $total_fixed += $value;
                break;
            case 'variable_expense':
                $total_variable += $value;
                break;
            case 'savings':
                $total_savings += $value;
                break;
        }
    }

    $total_expenses = $total_fixed + $total_variable + $total_savings;
    $monthly_saving = $total_income - $total_expenses;

    return array(
        'total_income'   => $total_income,
        'total_expenses' => $total_expenses,
        'total_fixed'    => $total_fixed,
        'total_variable' => $total_variable,
        'total_savings'  => $total_savings,
        'monthly_saving' => $monthly_saving,
    );
}
