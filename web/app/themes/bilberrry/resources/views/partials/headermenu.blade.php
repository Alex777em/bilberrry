@php use App\Walkers\MegaMenuWalker; @endphp

@if (has_nav_menu('primary_navigation'))
   <div class="primary-menu">
            <nav class="mega-menu-nav relative">
                {!! wp_nav_menu(['theme_location' => 'primary_navigation',
          'walker' => new MegaMenuWalker(),
           'menu_class' => 'header-menu', 'echo' => false]) !!}
            </nav>
        </div>
    </div>
@endif
