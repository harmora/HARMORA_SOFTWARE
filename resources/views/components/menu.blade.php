<?php

use App\Models\User;
use App\Models\Workspace;
use App\Models\LeaveRequest;
use Chatify\ChatifyMessenger;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$user = getAuthenticatedUser();


$pending_todos_count = $user->todos(0)->count();
$ongoing_meetings_count = $user->meetings('ongoing')->count();


?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme menu-container">
    <div class="app-brand demo">
        <a href="/home" class="app-brand-link">
            <span class="app-brand-logo demo">
                  <img src="{{asset($general_settings['full_logo'])}}" width="200px" alt="" />
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Taskify</span> -->
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>



    <div class="btn-group dropend px-2">
        <a href="/" class="btn btn-primary"  aria-haspopup="true" aria-expanded="false">
            {{-- {{ strlen($current_workspace->title) > 22 ? substr($current_workspace->title, 0, 22) . '...' : $current_workspace->title }}
             --}}
             Entreprise workspace   {{-- addtolang --}}
        </a>
    </div>



    <ul class="menu-inner py-1">
        <hr class="dropdown-divider" />

        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('home') ? 'active' : '' }}">
            <a href="/home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle text-danger"></i>
                <div><?= get_label('dashboard', 'Dashboard') ?></div>
            </a>
        </li>
<!--codes i added-->
        <li class="menu-item {{ Request::is('commandes') || Request::is('commandes/*') ? 'active' : '' }}">
            <a href="/commandes" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check text-dark'></i>
                <div><?= get_label('commandes', 'Commandes') ?>

                </div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('achats') || Request::is('achats/*') ? 'active' : '' }}">
            <a href="/achats" class="menu-link">
                <i class='menu-icon tf-icons bx bx-cart text-dark'></i>
                <div><?= get_label('achats', 'Achats') ?>
                </div>
            </a>
        </li>
<!--it ends here-->
        <li class="menu-item {{ Request::is('todos') || Request::is('todos/*') ? 'active' : '' }}">
            <a href="/todos" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check text-warning'></i>
                <div><?= get_label('todos', 'Todos') ?>
                    @if($pending_todos_count > 0)
                    <span class="flex-shrink-0 badge badge-center bg-danger w-px-20 h-px-20">{{$pending_todos_count}}</span>
                    @endif
                </div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('meetings') || Request::is('meetings/*') ? 'active' : '' }}">
            <a href="/meetings" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shape-polygon text-success"></i>
                <div><?= get_label('meetings', 'Meetings') ?>
                    @if($ongoing_meetings_count > 0)
                    <span class="flex-shrink-0 badge badge-center bg-success w-px-20 h-px-20">{{$ongoing_meetings_count}}</span>
                    @endif
                </div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('users') || Request::is('users/*') ? 'active' : '' }}">
            <a href="/users" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-primary"></i>
                <div><?= get_label('users', 'Users') ?></div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('entreprises') || Request::is('entreprises/*') ? 'active' : '' }}">
            <a href="/entreprises" class="menu-link">
                <i class="menu-icon fas fa-building bx-group text-primary" style="font-size:20px"></i>
                <div><?= get_label('entreprises', 'Entreprises') ?></div>
            </a>
        </li>


        <li class="menu-item {{ Request::is('clients') || Request::is('clients/*') ? 'active' : '' }}">
            <a href="/clients" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-warning"></i>
                <div><?= get_label('clients', 'Clients') ?></div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('fournisseurs') || Request::is('fournisseurs/*') ? 'active' : '' }}">
            <a href="/fournisseurs" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group text-secondary"></i>
                <div><?= get_label('fournisseurs', 'Fournisseurs') ?></div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('products') || Request::is('products/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-box text-success'></i>
                <div><?= get_label('stock', 'Stock')  ?></div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('products') ? 'active' : '' }}">
                    <a href="/products" class="menu-link">
                        <div><?= get_label('my_stock', 'My Stock') ?></div>
                    </a>
                </li>
                <li class="menu-item  {{ Request::is('products/movements') ? 'active' : '' }}">
                    <a href="{{ route('products.movements') }}" class="menu-link">
                        <div><?= get_label('stock_movements', 'Stock Movements') ?></div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a href="/documents" class="menu-link">
                <i class='menu-icon tf-icons bx bx bx-file text-warning'></i>
                <div><?= get_label('my documents', 'My Documents')  ?></div>
            </a>
        </li>
        <li class="menu-item">
            <a href="/disponibility" class="menu-link ">
                <i class='menu-icon tf-icons bx bx-calendar text-info'></i>
                <div><?= get_label('disponibilite', 'Disponibility')  ?></div>
            </a>
        </li>



        <li class="menu-item {{ Request::is('notes') || Request::is('notes/*') ? 'active' : '' }}">
            <a href="/notes" class="menu-link">
                <i class='menu-icon tf-icons bx bx-notepad text-primary'></i>
                <div><?= get_label('notes', 'Notes') ?></div>
            </a>
        </li>




         {{--    @role('admin')  --}}
        <li class="menu-item {{ Request::is('settings') || Request::is('roles/*') || Request::is('settings/*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box text-dark"></i>
                <div data-i18n="User interface"><?= get_label('settings', 'Settings') ?></div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('settings/general') ? 'active' : '' }}">
                    <a href="/settings/general" class="menu-link">
                        <div><?= get_label('general', 'General') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/permission') || Request::is('roles/*') ? 'active' : '' }}">
                    <a href="/settings/permission" class="menu-link">
                        <div><?= get_label('permissions', 'Permissions') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/languages') || Request::is('settings/languages/create') ? 'active' : '' }}">
                    <a href="/settings/languages" class="menu-link">
                        <div><?= get_label('languages', 'Languages') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/email') ? 'active' : '' }}">
                    <a href="/settings/email" class="menu-link">
                        <div><?= get_label('email', 'Email') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/sms-gateway') ? 'active' : '' }}">
                    <a href="/settings/sms-gateway" class="menu-link">
                        <div><?= get_label('sms_gateway_wa', 'SMS gateway/WhatsApp') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/pusher') ? 'active' : '' }}">
                    <a href="/settings/pusher" class="menu-link">
                        <div><?= get_label('pusher', 'Pusher') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/media-storage') ? 'active' : '' }}">
                    <a href="/settings/media-storage" class="menu-link">
                        <div><?= get_label('media_storage', 'Media storage') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/templates') ? 'active' : '' }}">
                    <a href="/settings/templates" class="menu-link">
                        <div><?= get_label('notification_templates', 'Notification Templates') ?></div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('settings/system-updater') ? 'active' : '' }}">
                    <a href="/settings/system-updater" class="menu-link">
                        <div><?= get_label('system_updater', 'System updater') ?></div>
                    </a>
                </li>
            </ul>
        </li>
        {{--       @endrole  --}}
    </ul>
</aside>
