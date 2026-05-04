<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function net_worth_get_default_config() {
    $categories = array(
        'liquid_assets' => array(
            'id'     => 'liquid_assets',
            'name'   => 'Likvidní prostředky',
            'slug'   => 'liquid_assets',
            'type'   => 'asset',
            'order'  => 1,
            'active' => true,
        ),
        'investments' => array(
            'id'     => 'investments',
            'name'   => 'Investice',
            'slug'   => 'investments',
            'type'   => 'asset',
            'order'  => 2,
            'active' => true,
        ),
        'real_estate' => array(
            'id'     => 'real_estate',
            'name'   => 'Nemovitosti',
            'slug'   => 'real_estate',
            'type'   => 'asset',
            'order'  => 3,
            'active' => true,
        ),
        'personal_property' => array(
            'id'     => 'personal_property',
            'name'   => 'Osobní majetek',
            'slug'   => 'personal_property',
            'type'   => 'asset',
            'order'  => 4,
            'active' => true,
        ),
        'mortgages' => array(
            'id'     => 'mortgages',
            'name'   => 'Hypotéky',
            'slug'   => 'mortgages',
            'type'   => 'liability',
            'order'  => 5,
            'active' => true,
        ),
        'consumer_loans' => array(
            'id'     => 'consumer_loans',
            'name'   => 'Spotřebitelské úvěry',
            'slug'   => 'consumer_loans',
            'type'   => 'liability',
            'order'  => 6,
            'active' => true,
        ),
        'credit_cards' => array(
            'id'     => 'credit_cards',
            'name'   => 'Kreditky a kontokorenty',
            'slug'   => 'credit_cards',
            'type'   => 'liability',
            'order'  => 7,
            'active' => true,
        ),
        'personal_debts' => array(
            'id'     => 'personal_debts',
            'name'   => 'Osobní dluhy',
            'slug'   => 'personal_debts',
            'type'   => 'liability',
            'order'  => 8,
            'active' => true,
        ),
    );

    $items = array(
        // --- Likvidní prostředky ---
        'cash' => array(
            'id'            => 'cash',
            'name'          => 'Hotovost',
            'slug'          => 'cash',
            'type'          => 'asset',
            'subtype'       => 'liquid_cash',
            'category'      => 'liquid_assets',
            'tooltip'       => 'Peníze, které máte fyzicky k dispozici.',
            'default_value' => 0,
            'order'         => 1,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'checking_accounts' => array(
            'id'            => 'checking_accounts',
            'name'          => 'Běžné účty',
            'slug'          => 'checking_accounts',
            'type'          => 'asset',
            'subtype'       => 'liquid_cash',
            'category'      => 'liquid_assets',
            'tooltip'       => 'Zůstatky na běžných účtech, ze kterých můžete ihned čerpat.',
            'default_value' => 0,
            'order'         => 2,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'savings_accounts' => array(
            'id'            => 'savings_accounts',
            'name'          => 'Spořicí účty',
            'slug'          => 'savings_accounts',
            'type'          => 'asset',
            'subtype'       => 'liquid_cash',
            'category'      => 'liquid_assets',
            'tooltip'       => 'Peníze na spořicích účtech, které jsou obvykle rychle dostupné.',
            'default_value' => 0,
            'order'         => 3,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'short_term_reserves' => array(
            'id'            => 'short_term_reserves',
            'name'          => 'Krátkodobé rezervy',
            'slug'          => 'short_term_reserves',
            'type'          => 'asset',
            'subtype'       => 'liquid_cash',
            'category'      => 'liquid_assets',
            'tooltip'       => 'Rezervy určené pro krátké časové horizonty, například termínované vklady.',
            'default_value' => 0,
            'order'         => 4,
            'active'        => true,
            'is_liquid'     => true,
        ),
        // --- Investice ---
        'stocks' => array(
            'id'            => 'stocks',
            'name'          => 'Akcie',
            'slug'          => 'stocks',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální tržní hodnota vašich akcií.',
            'default_value' => 0,
            'order'         => 5,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'etf' => array(
            'id'            => 'etf',
            'name'          => 'ETF',
            'slug'          => 'etf',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální tržní hodnota vašich ETF fondů.',
            'default_value' => 0,
            'order'         => 6,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'crypto' => array(
            'id'            => 'crypto',
            'name'          => 'Kryptoměny',
            'slug'          => 'crypto',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Odhad aktuální tržní hodnoty vašich kryptoměn.',
            'default_value' => 0,
            'order'         => 7,
            'active'        => true,
            'is_liquid'     => true,
        ),
        'precious_metals' => array(
            'id'            => 'precious_metals',
            'name'          => 'Drahé kovy',
            'slug'          => 'precious_metals',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Odhadovaná tržní hodnota drahých kovů.',
            'default_value' => 0,
            'order'         => 8,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'pension_savings' => array(
            'id'            => 'pension_savings',
            'name'          => 'Penzijní spoření',
            'slug'          => 'pension_savings',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální hodnota penzijního spoření.',
            'default_value' => 0,
            'order'         => 9,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'building_savings' => array(
            'id'            => 'building_savings',
            'name'          => 'Stavební spoření',
            'slug'          => 'building_savings',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální hodnota stavebního spoření.',
            'default_value' => 0,
            'order'         => 10,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'bonds' => array(
            'id'            => 'bonds',
            'name'          => 'Dluhopisy',
            'slug'          => 'bonds',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální tržní hodnota dluhopisů.',
            'default_value' => 0,
            'order'         => 11,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'mutual_funds' => array(
            'id'            => 'mutual_funds',
            'name'          => 'Podílové fondy',
            'slug'          => 'mutual_funds',
            'type'          => 'asset',
            'subtype'       => 'investment',
            'category'      => 'investments',
            'tooltip'       => 'Aktuální tržní hodnota podílových fondů.',
            'default_value' => 0,
            'order'         => 12,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Nemovitosti ---
        'real_estate_flat' => array(
            'id'            => 'real_estate_flat',
            'name'          => 'Byt',
            'slug'          => 'real_estate_flat',
            'type'          => 'asset',
            'subtype'       => 'real_estate',
            'category'      => 'real_estate',
            'tooltip'       => 'Odhadovaná tržní cena nemovitosti. Nejde o kupní cenu, ale o dnešní hodnotu.',
            'default_value' => 0,
            'order'         => 13,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'real_estate_house' => array(
            'id'            => 'real_estate_house',
            'name'          => 'Rodinný dům',
            'slug'          => 'real_estate_house',
            'type'          => 'asset',
            'subtype'       => 'real_estate',
            'category'      => 'real_estate',
            'tooltip'       => 'Odhadovaná tržní cena rodinného domu.',
            'default_value' => 0,
            'order'         => 14,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'real_estate_cottage' => array(
            'id'            => 'real_estate_cottage',
            'name'          => 'Chata / chalupa',
            'slug'          => 'real_estate_cottage',
            'type'          => 'asset',
            'subtype'       => 'real_estate',
            'category'      => 'real_estate',
            'tooltip'       => 'Odhadovaná tržní cena chaty nebo chalupy.',
            'default_value' => 0,
            'order'         => 15,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'real_estate_land' => array(
            'id'            => 'real_estate_land',
            'name'          => 'Pozemky',
            'slug'          => 'real_estate_land',
            'type'          => 'asset',
            'subtype'       => 'real_estate',
            'category'      => 'real_estate',
            'tooltip'       => 'Odhadovaná tržní cena pozemků.',
            'default_value' => 0,
            'order'         => 16,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'real_estate_investment' => array(
            'id'            => 'real_estate_investment',
            'name'          => 'Investiční nemovitost',
            'slug'          => 'real_estate_investment',
            'type'          => 'asset',
            'subtype'       => 'real_estate',
            'category'      => 'real_estate',
            'tooltip'       => 'Odhadovaná tržní cena investiční nemovitosti.',
            'default_value' => 0,
            'order'         => 17,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Osobní majetek ---
        'car' => array(
            'id'            => 'car',
            'name'          => 'Auto',
            'slug'          => 'car',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Odhad aktuální prodejní ceny vozidla.',
            'default_value' => 0,
            'order'         => 18,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'motorcycle' => array(
            'id'            => 'motorcycle',
            'name'          => 'Motorka',
            'slug'          => 'motorcycle',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Odhad aktuální prodejní ceny motorky.',
            'default_value' => 0,
            'order'         => 19,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'collections' => array(
            'id'            => 'collections',
            'name'          => 'Drahé sbírky',
            'slug'          => 'collections',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Odhadovaná hodnota drahých sbírek.',
            'default_value' => 0,
            'order'         => 20,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'art' => array(
            'id'            => 'art',
            'name'          => 'Umělecká díla',
            'slug'          => 'art',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Odhadovaná tržní hodnota uměleckých děl.',
            'default_value' => 0,
            'order'         => 21,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'nft' => array(
            'id'            => 'nft',
            'name'          => 'NFT a digitální sbírky',
            'slug'          => 'nft',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Odhadovaná hodnota NFT a digitálních sbírek.',
            'default_value' => 0,
            'order'         => 22,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'furniture_valuable' => array(
            'id'            => 'furniture_valuable',
            'name'          => 'Vybavení domácnosti nad 50 000 Kč',
            'slug'          => 'furniture_valuable',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Hodnotné vybavení domácnosti.',
            'default_value' => 0,
            'order'         => 23,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'other_asset' => array(
            'id'            => 'other_asset',
            'name'          => 'Jiný hodnotný majetek',
            'slug'          => 'other_asset',
            'type'          => 'asset',
            'subtype'       => 'personal_property',
            'category'      => 'personal_property',
            'tooltip'       => 'Jiný majetek, který nelze zařadit do výše uvedených kategorií.',
            'default_value' => 0,
            'order'         => 24,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Hypotéky ---
        'mortgage_home' => array(
            'id'            => 'mortgage_home',
            'name'          => 'Zbývající jistina hypotéky na bydlení',
            'slug'          => 'mortgage_home',
            'type'          => 'liability',
            'subtype'       => 'mortgage',
            'category'      => 'mortgages',
            'tooltip'       => 'Zbývající jistina úvěru, tedy kolik ještě dlužíte bance.',
            'default_value' => 0,
            'order'         => 1,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'mortgage_investment' => array(
            'id'            => 'mortgage_investment',
            'name'          => 'Zbývající jistina hypotéky na investiční nemovitost',
            'slug'          => 'mortgage_investment',
            'type'          => 'liability',
            'subtype'       => 'mortgage',
            'category'      => 'mortgages',
            'tooltip'       => 'Zbývající jistina hypotéky na investiční nemovitost.',
            'default_value' => 0,
            'order'         => 2,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Spotřebitelské úvěry ---
        'loan_car' => array(
            'id'            => 'loan_car',
            'name'          => 'Půjčka na auto',
            'slug'          => 'loan_car',
            'type'          => 'liability',
            'subtype'       => 'consumer_loan',
            'category'      => 'consumer_loans',
            'tooltip'       => 'Zbývající částka půjčky na auto.',
            'default_value' => 0,
            'order'         => 3,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'loan_electronics' => array(
            'id'            => 'loan_electronics',
            'name'          => 'Půjčka na elektroniku',
            'slug'          => 'loan_electronics',
            'type'          => 'liability',
            'subtype'       => 'consumer_loan',
            'category'      => 'consumer_loans',
            'tooltip'       => 'Zbývající částka půjčky na elektroniku.',
            'default_value' => 0,
            'order'         => 4,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'loan_furniture' => array(
            'id'            => 'loan_furniture',
            'name'          => 'Půjčka na vybavení domácnosti',
            'slug'          => 'loan_furniture',
            'type'          => 'liability',
            'subtype'       => 'consumer_loan',
            'category'      => 'consumer_loans',
            'tooltip'       => 'Zbývající částka půjčky na vybavení domácnosti.',
            'default_value' => 0,
            'order'         => 5,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'loan_consolidation' => array(
            'id'            => 'loan_consolidation',
            'name'          => 'Konsolidace půjček',
            'slug'          => 'loan_consolidation',
            'type'          => 'liability',
            'subtype'       => 'consumer_loan',
            'category'      => 'consumer_loans',
            'tooltip'       => 'Zbývající částka konsolidované půjčky.',
            'default_value' => 0,
            'order'         => 6,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'loan_other' => array(
            'id'            => 'loan_other',
            'name'          => 'Jiný spotřebitelský úvěr',
            'slug'          => 'loan_other',
            'type'          => 'liability',
            'subtype'       => 'consumer_loan',
            'category'      => 'consumer_loans',
            'tooltip'       => 'Zbývající částka jiného spotřebitelského úvěru.',
            'default_value' => 0,
            'order'         => 7,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Kreditky a kontokorenty ---
        'credit_card' => array(
            'id'            => 'credit_card',
            'name'          => 'Vyčerpaná kreditní karta',
            'slug'          => 'credit_card',
            'type'          => 'liability',
            'subtype'       => 'credit_card',
            'category'      => 'credit_cards',
            'tooltip'       => 'Aktuálně vyčerpaná částka, i pokud ji plánujete splatit příští měsíc.',
            'default_value' => 0,
            'order'         => 8,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'overdraft' => array(
            'id'            => 'overdraft',
            'name'          => 'Vyčerpaný kontokorent',
            'slug'          => 'overdraft',
            'type'          => 'liability',
            'subtype'       => 'credit_card',
            'category'      => 'credit_cards',
            'tooltip'       => 'Aktuálně vyčerpaná částka kontokorentu.',
            'default_value' => 0,
            'order'         => 9,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'deferred_payments' => array(
            'id'            => 'deferred_payments',
            'name'          => 'Odložené platby',
            'slug'          => 'deferred_payments',
            'type'          => 'liability',
            'subtype'       => 'credit_card',
            'category'      => 'credit_cards',
            'tooltip'       => 'Celková hodnota odložených plateb.',
            'default_value' => 0,
            'order'         => 10,
            'active'        => true,
            'is_liquid'     => false,
        ),
        // --- Osobní dluhy ---
        'family_loan' => array(
            'id'            => 'family_loan',
            'name'          => 'Půjčky v rodině',
            'slug'          => 'family_loan',
            'type'          => 'liability',
            'subtype'       => 'personal_debt',
            'category'      => 'personal_debts',
            'tooltip'       => 'Částky, které dlužíte rodině, známým nebo jiným osobám.',
            'default_value' => 0,
            'order'         => 11,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'friend_loan' => array(
            'id'            => 'friend_loan',
            'name'          => 'Půjčky od známých',
            'slug'          => 'friend_loan',
            'type'          => 'liability',
            'subtype'       => 'personal_debt',
            'category'      => 'personal_debts',
            'tooltip'       => 'Dlužné částky přátelům.',
            'default_value' => 0,
            'order'         => 12,
            'active'        => true,
            'is_liquid'     => false,
        ),
        'other_personal_debt' => array(
            'id'            => 'other_personal_debt',
            'name'          => 'Jiné osobní závazky',
            'slug'          => 'other_personal_debt',
            'type'          => 'liability',
            'subtype'       => 'personal_debt',
            'category'      => 'personal_debts',
            'tooltip'       => 'Jiné osobní závazky.',
            'default_value' => 0,
            'order'         => 13,
            'active'        => true,
            'is_liquid'     => false,
        ),
    );

    $benchmarks = array(
        'debt_to_asset'    => array(
            'low_max'         => 30,
            'medium_max'      => 70,
            'high_from'       => 70,
        ),
        'crisis_resilience' => array(
            'red_max_months'    => 3,
            'orange_max_months' => 6,
            'green_from_months' => 6,
        ),
        'diversification'  => array(
            'max_single_asset_category_share' => 80,
        ),
        'monthly_expenses' => array(
            'default' => 30000,
            'min'     => 0,
            'max'     => 1000000,
            'step'    => 1000,
        ),
    );

    $result_messages = array(
        'negative_net_worth' => array(
            'key'    => 'negative_net_worth',
            'status' => 'red',
            'title'  => 'Záporné čisté jmění',
            'text'   => 'Vaše závazky převyšují aktiva. Je důležité začít aktivně snižovat dluhy a budovat finanční rezervy.',
        ),
        'low_net_worth' => array(
            'key'    => 'low_net_worth',
            'status' => 'orange',
            'title'  => 'Nízké čisté jmění',
            'text'   => 'Vaše čisté jmění je kladné, ale stále relativně nízké. Zaměřte se na tvorbu úspor a snižování závazků.',
        ),
        'medium_net_worth' => array(
            'key'    => 'medium_net_worth',
            'status' => 'orange',
            'title'  => 'Průměrné čisté jmění',
            'text'   => 'Jste na dobré cestě. Pravidelné investování vám pomůže dále budovat finanční nezávislost.',
        ),
        'high_net_worth' => array(
            'key'    => 'high_net_worth',
            'status' => 'green',
            'title'  => 'Silné čisté jmění',
            'text'   => 'Gratulujeme! Vaše finanční situace je velmi dobrá. Zvažte diverzifikaci a optimalizaci portfolia.',
        ),
    );

    $recommended_content = array(
        array(
            'key'        => 'diversify',
            'title'      => 'Jak diverzifikovat investiční portfolio',
            'url'        => '/investice/diverzifikace/',
            'conditions' => array( 'diversification_warning' ),
        ),
        array(
            'key'        => 'emergency_fund',
            'title'      => 'Jak vybudovat finanční rezervu',
            'url'        => '/osobni-finance/financni-rezerva/',
            'conditions' => array( 'low_crisis_resilience' ),
        ),
        array(
            'key'        => 'debt_reduction',
            'title'      => 'Jak splácet dluhy rychleji',
            'url'        => '/osobni-finance/splaceni-dluhu/',
            'conditions' => array( 'high_debt' ),
        ),
        array(
            'key'        => 'investing_basics',
            'title'      => 'Základy investování pro začátečníky',
            'url'        => '/investice/zaklady-investovani/',
            'conditions' => array( 'negative_net_worth', 'low_net_worth' ),
        ),
    );

    return array(
        'categories'          => $categories,
        'items'               => $items,
        'benchmarks'          => $benchmarks,
        'result_messages'     => $result_messages,
        'recommended_content' => $recommended_content,
    );
}

function net_worth_get_config( $post_id ) {
    $default = net_worth_get_default_config();

    $categories_raw          = get_post_meta( $post_id, '_net_worth_categories', true );
    $items_raw               = get_post_meta( $post_id, '_net_worth_items', true );
    $benchmarks_raw          = get_post_meta( $post_id, '_net_worth_benchmarks', true );
    $result_messages_raw     = get_post_meta( $post_id, '_net_worth_result_messages', true );
    $recommended_content_raw = get_post_meta( $post_id, '_net_worth_recommended_content', true );

    $categories          = ! empty( $categories_raw )          ? json_decode( $categories_raw, true )          : null;
    $items               = ! empty( $items_raw )               ? json_decode( $items_raw, true )               : null;
    $benchmarks          = ! empty( $benchmarks_raw )          ? json_decode( $benchmarks_raw, true )          : null;
    $result_messages     = ! empty( $result_messages_raw )     ? json_decode( $result_messages_raw, true )     : null;
    $recommended_content = ! empty( $recommended_content_raw ) ? json_decode( $recommended_content_raw, true ) : null;

    return array(
        'categories'          => ( is_array( $categories ) && ! empty( $categories ) )                   ? $categories          : $default['categories'],
        'items'               => ( is_array( $items ) && ! empty( $items ) )                             ? $items               : $default['items'],
        'benchmarks'          => ( is_array( $benchmarks ) && ! empty( $benchmarks ) )                   ? $benchmarks          : $default['benchmarks'],
        'result_messages'     => ( is_array( $result_messages ) && ! empty( $result_messages ) )         ? $result_messages     : $default['result_messages'],
        'recommended_content' => ( is_array( $recommended_content ) && ! empty( $recommended_content ) ) ? $recommended_content : $default['recommended_content'],
    );
}

function net_worth_get_categories( $post_id ) {
    $config = net_worth_get_config( $post_id );
    return $config['categories'];
}

function net_worth_get_items( $post_id ) {
    $config = net_worth_get_config( $post_id );
    return $config['items'];
}

function net_worth_get_benchmarks( $post_id ) {
    $config = net_worth_get_config( $post_id );
    return $config['benchmarks'];
}

function net_worth_get_result_messages( $post_id ) {
    $config = net_worth_get_config( $post_id );
    return $config['result_messages'];
}

function net_worth_get_recommended_content( $post_id ) {
    $config = net_worth_get_config( $post_id );
    return $config['recommended_content'];
}

function net_worth_get_saved_user_data( $post_id, $user_id ) {
    if ( ! $user_id ) {
        return null;
    }
    $raw = get_user_meta( $user_id, '_net_worth_current_data', true );
    if ( empty( $raw ) ) {
        return null;
    }
    $decoded = json_decode( $raw, true );
    if ( ! is_array( $decoded ) ) {
        return null;
    }
    if ( isset( $decoded['calculator_id'] ) && (int) $decoded['calculator_id'] !== (int) $post_id ) {
        return null;
    }
    return isset( $decoded['values'] ) ? $decoded['values'] : null;
}

function net_worth_get_user_snapshots( $post_id, $user_id ) {
    if ( ! $user_id ) {
        return array();
    }
    $raw = get_user_meta( $user_id, '_net_worth_snapshots', true );
    if ( empty( $raw ) ) {
        return array();
    }
    $decoded = json_decode( $raw, true );
    if ( ! is_array( $decoded ) ) {
        return array();
    }
    // Filter to only snapshots for this calculator
    $filtered = array();
    foreach ( $decoded as $snapshot ) {
        if ( isset( $snapshot['calculator_id'] ) && (int) $snapshot['calculator_id'] === (int) $post_id ) {
            $filtered[] = $snapshot;
        }
    }
    return $filtered;
}

function net_worth_sanitize_money_value( $value ) {
    $float = floatval( $value );
    return $float < 0 ? 0.0 : $float;
}

function net_worth_sanitize_values( $values ) {
    if ( ! is_array( $values ) ) {
        return array();
    }
    $clean = array();
    foreach ( $values as $key => $val ) {
        $clean[ sanitize_key( $key ) ] = net_worth_sanitize_money_value( $val );
    }
    return $clean;
}

function net_worth_validate_values( $values ) {
    if ( ! is_array( $values ) ) {
        return false;
    }
    foreach ( $values as $val ) {
        if ( ! is_numeric( $val ) || floatval( $val ) < 0 ) {
            return false;
        }
    }
    return true;
}

function net_worth_calculate_results( $values, $items, $benchmarks ) {
    $total_assets      = 0.0;
    $total_liabilities = 0.0;
    $liquid_assets     = 0.0;
    $category_totals   = array();

    foreach ( $items as $item ) {
        if ( ! ( $item['active'] ?? true ) ) {
            continue;
        }
        $slug  = $item['slug'] ?? $item['id'] ?? '';
        $value = isset( $values[ $slug ] ) ? floatval( $values[ $slug ] ) : 0.0;
        $type  = $item['type'] ?? '';

        if ( $value <= 0 ) {
            continue;
        }

        if ( $type === 'asset' ) {
            $total_assets += $value;
            if ( ! empty( $item['is_liquid'] ) ) {
                $liquid_assets += $value;
            }
            $cat = $item['category'] ?? 'other';
            if ( ! isset( $category_totals[ $cat ] ) ) {
                $category_totals[ $cat ] = 0.0;
            }
            $category_totals[ $cat ] += $value;
        } elseif ( $type === 'liability' ) {
            $total_liabilities += $value;
        }
    }

    $net_worth    = $total_assets - $total_liabilities;
    $equity_ratio = $total_assets > 0 ? ( $net_worth / $total_assets ) * 100.0 : 0.0;
    $debt_to_asset_ratio = $total_assets > 0 ? ( $total_liabilities / $total_assets ) * 100.0 : 0.0;
    $liquidity_index     = $total_assets > 0 ? ( $liquid_assets / $total_assets ) * 100.0 : 0.0;

    $monthly_expenses_default = isset( $benchmarks['monthly_expenses']['default'] ) ? floatval( $benchmarks['monthly_expenses']['default'] ) : 30000.0;
    $monthly_expenses         = isset( $values['monthly_expenses'] ) ? floatval( $values['monthly_expenses'] ) : $monthly_expenses_default;
    $crisis_resilience_months = $monthly_expenses > 0 ? ( $liquid_assets / $monthly_expenses ) : null;

    // Largest asset category
    $largest_asset_category       = '';
    $largest_asset_category_value = 0.0;
    foreach ( $category_totals as $cat_slug => $cat_value ) {
        if ( $cat_value > $largest_asset_category_value ) {
            $largest_asset_category_value = $cat_value;
            $largest_asset_category       = $cat_slug;
        }
    }

    $largest_asset_category_share = $total_assets > 0 ? ( $largest_asset_category_value / $total_assets ) * 100.0 : 0.0;
    $max_share = isset( $benchmarks['diversification']['max_single_asset_category_share'] )
        ? floatval( $benchmarks['diversification']['max_single_asset_category_share'] )
        : 80.0;
    $diversification_warning = $total_assets > 0 && $largest_asset_category_share > $max_share;

    return array(
        'total_assets'                 => round( $total_assets ),
        'total_liabilities'            => round( $total_liabilities ),
        'net_worth'                    => round( $net_worth ),
        'equity_ratio'                 => round( $equity_ratio, 1 ),
        'debt_to_asset_ratio'          => round( $debt_to_asset_ratio, 1 ),
        'liquid_assets'                => round( $liquid_assets ),
        'liquidity_index'              => round( $liquidity_index, 1 ),
        'crisis_resilience_months'     => $crisis_resilience_months !== null ? round( $crisis_resilience_months, 1 ) : null,
        'monthly_expenses'             => round( $monthly_expenses ),
        'category_totals'              => $category_totals,
        'largest_asset_category'       => $largest_asset_category,
        'largest_asset_category_share' => round( $largest_asset_category_share, 1 ),
        'diversification_warning'      => $diversification_warning,
    );
}
