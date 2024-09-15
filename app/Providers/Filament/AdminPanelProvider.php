<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use App\Filament\Resources\CategoryNilaiResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\PeriodeResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\StudentResource\Widgets\StatsOverview;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->viteTheme('resources/css/filament/admin/theme.css')
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn(): string => Dashboard::getUrl()),

                        ]),
                    NavigationGroup::make('Academic')
                        ->items([
                            ...TeacherResource::getNavigationItems(),
                            ...StudentResource::getNavigationItems(),
                            ...SubjectResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Source')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...DepartmentResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            NavigationItem::make('Roles')
                                ->icon('heroicon-o-user-group')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.roles.index',
                                    'filament.admin.resources.roles.create',
                                    'filament.admin.resources.roles.view',
                                    'filament.admin.resources.roles.edit',
                                ]))
                                ->url(fn (): string => '/admin/roles'),
                            NavigationItem::make('Permission')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.permissions.index',
                                    'filament.admin.resources.permissions.create',
                                    'filament.admin.resources.permissions.view',
                                    'filament.admin.resources.permissions.edit',
                                ]))
                                ->url(fn (): string => '/admin/permissions'),
                            NavigationItem::make('User')
                                ->icon('heroicon-o-users')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.users.index',
                                    'filament.admin.resources.users.create',
                                    'filament.admin.resources.users.view',
                                    'filament.admin.resources.users.edit',
                                ]))
                                ->url(fn (): string => '/admin/users'),
                        ]),
                ]);
            });;
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Setting')
                    ->url(PeriodeResource::getUrl())
                    ->icon('heroicon-o-cog'),
            ]);
        });
    }
}
