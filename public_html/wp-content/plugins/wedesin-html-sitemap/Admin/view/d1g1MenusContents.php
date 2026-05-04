<?php

namespace sitemap\admin\view;

if (!defined('ABSPATH')) {

    exit;
}
/**
 * d1g1MenusContents
 *  obsah navigace a jednotlivých tabů
 * 
 * @author Digihood
 */
if (!class_exists('d1g1MenusContents')) {
    class d1g1MenusContents
    {


        function __construct()
        {
            add_action('d1g1_navigations-' . D1G1_SITEMAP, [$this, 'add_navigations']);
            add_filter('d1g1_navigation_content-' . D1G1_SITEMAP, [$this, 'add_navigation_contents']);
        }
        /**
         * zobrazní navigační panely
         * 
         * @param $ŧab = get jmeno tabu
         * 
         * @author Digihood
         */
        public static function add_navigations($tab)
        {
            echo '<a href="?page=' . D1G1_SITEMAP . '" class="nav-tab ' . ($tab === null ? 'nav-tab-active' : '') . '">' . d1g1_get_svg(D1G1_PATHTOFWASSET_sitemap . "icons/home-line.svg") . __('Nastavení', 'digiSM') . '</a>';
            echo '<a href="?page=' . D1G1_SITEMAP . '&tab=documentation" class="nav-tab ' . ($tab === 'documentation' ? 'nav-tab-active' : '') . '">' . d1g1_get_svg(D1G1_PATHTOFWASSET_sitemap . "icons/info-standard-line.svg") . __('Dokumentace', 'digiSM') . '</a>';
        }
        /**
         * zobrazní navigačním panelum obsah 
         * 
         * @param $contents = array [jmeno tabu => jmeno fieldu]
         * 
         * @author Digihood
         * @return array
         */
        public static function add_navigation_contents($contents)
        {
            $contents['default'] = 'mainSettings';
            $contents['documentation'] = [__CLASS__, 'documentation'];

            return $contents;
        }

        public static function documentation()
        {
            if (isset($_GET['tab']) && $_GET['tab'] == 'documentation') {
?>
                <div class="functionality-description">
                    <p class="plugin-introduction">
                        <?= __('Digihood HTML Sitemap je plugin pro tvorbu jednoduchých sitemap pomocí shortcodů. Vytvořte mapu celého webu nebo pouze specifikovaných postů/stránek/custom post typů, taxonomií a jejich termů, postů pod specifikovanou taxonomií nebo jejím termem, nebo příloh. Sitemapy jsou přizpůsobitelné pomocí šablon nebo vlastního CSS s minimálním výchozím stylováním. Obsahuje black-list možnosti.', 'digiSM') ?>
                    </p>

                    <ol class="plugin-explanation">
                        <!-- 1 -->
                        <li> <?= __('Sitemapa celého webu:', 'digiSM') ?> </li>

                        <ul>
                            <li><?= __('Příklad: ', 'digiSM') .  '[html-sitemap exclude="1,349" exclude_type="book,product"]' ?></li>
                            <li><?= __('(exclude a exclude_type jsou volitelné parametry pro černou listinu - nefunguje pro typ příspěvku "page", vstupy jsou ID příspěvků a klíče typů příspěvků)', 'digiSM') ?></li>
                        </ul>
                        <!-- 2 -->
                        <li> <?= __('Sitemapa příloh:', 'digiSM') ?> <a href="https://www.freeformatter.com/mime-types-list.html"> <?= __('Seznam MIME kódů', 'digiSM') ?> </a> </li>
                        <ul>
                            <li><?= __('výpis všech příloh specifikovaného typu z administrace WordPressu.', 'digiSM') ?> </li>
                            <li><?= __('Příklad: ', 'digiSM') . '[attachment-sitemap file-type="application/pdf"]' ?> </li>
                            <li><?= __('file-type není povinný parametr a používá MIME kódy, výchozí: všechny dokumenty', 'digiSM') ?> </li>
                        </ul>
                        <!-- 3 -->
                        <li> <?= __('Sitemapa příspěvků pod post-typy:', 'digiSM') ?> </li>
                        <ul>
                            <li> <?= __('výpis všech příspěvků pod specifikovaným typem příspěvku, post-type parametr je povinný', 'digiSM') ?> </li>
                            <li> <?= __('Příklad: ', 'digiSM') . '[post-type-sitemap post-type="post"]' ?> </li>
                            <li> <?= __('post-type je povinný parametr, který specifikuje typ příspěvku, který chcete zahrnout', 'digiSM') ?> </li>
                        </ul>
                        <!-- 4 -->
                        <li> <?= __('Příspěvky pod taxonomií nebo termem:', 'digiSM') ?></li>
                        <ul>
                            <li> <?= __('výpis všech příspěvků pod specifikovanou taxonomií/termínem', 'digiSM') ?> </li>
                            <li> <?= __('Příklad: ', 'digiSM') . '[tax-post-sitemap taxonomy="category" term="news"]' ?> </li>
                        </ul>
                        <!-- 5 -->
                        <li> <?= __('Sitemapa termů taxonomií:', 'digiSM') ?></li>
                        <ul>
                            <li> <?= __('výpis všech termínů pod specifikovanou taxonomií', 'digiSM') ?> </li>
                            <li> <?= __('taxonomy je povinný, volitelný parametr hide-empty="true/false" - výchozí = false', 'digiSM') ?> </li>
                            <li> <?= __('Příklad: ', 'digiSM') . '[tax-term-sitemap taxonomy="post_tag"]' ?> </li>
                        </ul>
                    </ol>
                    <p class="note">
                        <strong> <?= __('Poznámka:', 'digiSM') ?> </strong> <?= __('Do kódu nebylo přidáno žádné další stylování kromě style="clear: both;" a třídy "whs-wrap", která obklopuje celý seznam a může být použita pro další osobní stylování. Doporučujeme přidávat své stylování mimo samotný plugin, protože změny by mohly být přepsány budoucími aktualizacemi.', 'digiSM') ?>
                    </p>
                </div>
<?php
            }
        }
    }
    new d1g1MenusContents;
}
