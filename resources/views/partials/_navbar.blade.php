<!-- Navbar -->
<?php
use App\Models\Language;
use App\Models\Notification;
$authenticatedUser = getAuthenticatedUser();
$current_language = Language::where('code', app()->getLocale())->get(['name', 'code']);
$default_language = $authenticatedUser->lang;

?>
@authBoth
<div id="section-not-to-print">
    <nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="nav-item">
                <i class="bx bx-search"></i><span id="global-search"></span>
            </div>
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                @if (config('constants.ALLOW_MODIFICATION') === 0)
                <li><span class="badge bg-danger demo-mode">Demo mode</span></li>
                @endif

                <li class="nav-item navbar-dropdown dropdown ml-1">
                    <div class="btn-group dropend px-1">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="icon-only"><i class='bx bx-globe'></i></span> <span class="language-name"><?= $current_language[0]['name'] ?? '' ?></span>
                        </button>
                        <ul class="dropdown-menu language-dropdown" id="languageDropdown">
                            @foreach ($languages as $language)
                            <?php $checked = $language->code == app()->getLocale() ? "<i class='menu-icon tf-icons bx bx-check-square text-primary'></i>" : "<i class='menu-icon tf-icons bx bx-square text-solid'></i>" ?>
                            <li class="dropdown-item">
                                <a href="{{ url('/settings/languages/switch/' . $language->code) }}">
                                    <?= $checked ?>
                                    {{ $language->name }}
                                </a>
                            </li>
                            @endforeach
                            <li>
                                <hr class="dropdown-divider">
                           </li>
                      @if ($current_language[0]['code'] == $default_language)
                            <li><span class="badge bg-primary mx-5 mb-1 mt-1" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('current_language_is_your_primary_language', 'Current language is your primary language') ?>"><?= get_label('primary', 'Primary') ?></span></li>
                            @else
                            <a href="javascript:void(0);"><span class="badge bg-secondary mx-5 mb-1 mt-1" id="set-as-default" data-lang="{{app()->getLocale()}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('set_current_language_as_your_primary_language', 'Set current language as your primary language') ?>"><?= get_label('set_as_primary', 'Set as primary') ?></span></a>
                          @endif
                        </ul>
                    </div>
                    </button>
                </li>
                <li class="nav-item navbar-dropdown dropdown mt-3 mx-2">
                    <p class="nav-item">
                        <span class="nav-mobile-hidden"><?= get_label('hi', 'Hi') ?>👋</span>
                        <span class="nav-mobile-hidden">{{$authenticatedUser->first_name}}</span>
                    </p>
                </li>
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{$authenticatedUser->photo ? asset('storage/' . $authenticatedUser->photo) : asset('storage/photos/no-image.jpg')}}" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{$authenticatedUser->photo ? asset('storage/' . $authenticatedUser->photo) : asset('storage/photos/no-image.jpg')}}" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{$authenticatedUser->first_name}} {{$authenticatedUser->last_name}}</span>
                                        <small class="text-muted text-capitalize">
                                            {{ucfirst($authenticatedUser->getRoleNames()->first())}}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/account/{{ $authenticatedUser->id }}">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle"><?= get_label('my_profile', 'My Profile') ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/preferences">
                                <i class='bx bx-cog me-2'></i>
                                <span class="align-middle"><?= get_label('preferences', 'Preferences') ?></span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <form action="/logout" method="POST" class="dropdown-item">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-log-out-circle"></i> <?= get_label('logout', 'Logout') ?></button>
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </nav>
</div>
@else
@endauth
<script>
    var label_search = '<?= get_label('search', 'Search') ?>';
</script>
<script src="{{asset('assets/js/pages/navbar.js')}}"></script>
<!-- / Navbar -->
