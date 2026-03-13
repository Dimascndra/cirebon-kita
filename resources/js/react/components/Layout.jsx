import React, { useEffect, useMemo, useState } from 'react';
import { Link, NavLink, useLocation } from 'react-router-dom';
import Icon from './Icon';

const navigation = [
    { label: 'Home', to: '/' },
    { label: 'Berita', to: '/berita' },
    { label: 'Lowongan', to: '/lowongan' },
    { label: 'Perusahaan', to: '/perusahaan' },
    { label: 'Tentang', to: '/tentang' },
];

function hasRole(user, roleNames) {
    const names = (user?.roles || []).map((role) => role.name);
    return roleNames.some((role) => names.includes(role));
}

export default function Layout({ children, user, onLogout }) {
    const location = useLocation();
    const isAdmin = hasRole(user, ['SuperAdmin', 'Admin']);
    const isCompany = hasRole(user, ['Company']);
    const isApplicant = user && !isAdmin && !isCompany;
    const [menuOpen, setMenuOpen] = useState(false);
    const [theme, setTheme] = useState(() => {
        if (typeof window === 'undefined') {
            return 'light';
        }

        return window.localStorage.getItem('cirebon-kita-public-theme') || 'light';
    });

    const isPublicPage = useMemo(() => {
        const path = location.pathname;

        return !(
            path === '/dashboard' ||
            path === '/profile' ||
            path.startsWith('/my-applications') ||
            path.startsWith('/company/') ||
            path.startsWith('/admin/')
        );
    }, [location.pathname]);

    const workspaceLinks = useMemo(() => {
        if (isAdmin) {
            return [
                { label: 'Admin Dashboard', to: '/admin/dashboard' },
                { label: 'Users', to: '/admin/users' },
                { label: 'Roles', to: '/admin/roles' },
                { label: 'News', to: '/admin/news' },
                { label: 'Jobs', to: '/admin/jobs' },
                { label: 'Ads', to: '/admin/ads' },
            ];
        }

        if (isCompany) {
            return [
                { label: 'Company Dashboard', to: '/company/dashboard' },
                { label: 'Applicants', to: '/company/applicants' },
            ];
        }

        if (isApplicant) {
            return [
                { label: 'Dashboard', to: '/dashboard' },
                { label: 'Profile', to: '/profile' },
                { label: 'Applications', to: '/my-applications' },
            ];
        }

        return [];
    }, [isAdmin, isApplicant, isCompany]);

    useEffect(() => {
        if (typeof window === 'undefined') {
            return;
        }

        window.localStorage.setItem('cirebon-kita-public-theme', theme);
        document.documentElement.style.colorScheme = theme;
    }, [theme]);

    const isDarkTheme = isPublicPage && theme === 'dark';

    return (
        <div className={`min-h-screen bg-gray-50 ${isPublicPage ? 'public-theme' : ''} ${isDarkTheme ? 'dark' : ''}`}>
            <header className="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950/95">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        {/* Logo */}
                        <Link className="flex items-center space-x-3" to="/" onClick={() => setMenuOpen(false)}>
                            <div className="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-lg">CK</span>
                            </div>
                            <div>
                                <span className="text-xl font-bold text-gray-900 dark:text-slate-100">CirebonKita</span>
                                <span className="block text-xs text-gray-500 dark:text-slate-400">Portal karier dan berita regional</span>
                            </div>
                        </Link>

                        {/* Desktop Navigation */}
                        <nav className="hidden lg:flex items-center space-x-8">
                            {navigation.map((item) => (
                                <NavLink
                                    key={item.to}
                                    to={item.to}
                                    onClick={() => setMenuOpen(false)}
                                    className={({ isActive }) =>
                                        isActive
                                            ? 'text-blue-600 font-medium dark:text-cyan-400'
                                            : 'text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white'
                                    }
                                >
                                    {item.label}
                                </NavLink>
                            ))}
                        </nav>

                        {/* Desktop Actions */}
                        <div className="hidden lg:flex items-center space-x-4">
                            {isPublicPage && (
                                <button
                                    type="button"
                                    className="inline-flex items-center rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                    onClick={() => setTheme((current) => (current === 'dark' ? 'light' : 'dark'))}
                                    aria-pressed={isDarkTheme}
                                >
                                    <Icon name={isDarkTheme ? 'sun' : 'moon'} className="mr-2 h-4 w-4" />
                                    {isDarkTheme ? 'Mode terang' : 'Mode gelap'}
                                </button>
                            )}
                            {isAdmin && (
                                <Link className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" to="/admin/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="shield" className="w-4 h-4 mr-2" />
                                    Admin
                                </Link>
                            )}
                            {isCompany && (
                                <Link className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" to="/company/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="building" className="w-4 h-4 mr-2" />
                                    Company
                                </Link>
                            )}
                            {isApplicant && (
                                <Link className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" to="/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="user" className="w-4 h-4 mr-2" />
                                    Dashboard
                                </Link>
                            )}
                            {user ? (
                                <>
                                    <div className="flex items-center space-x-2">
                                        <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center dark:bg-slate-700">
                                            <Icon name="user" className="w-4 h-4 text-gray-600" />
                                        </div>
                                        <span className="text-sm font-medium text-gray-700 dark:text-slate-200">{user.name}</span>
                                    </div>
                                    <Link className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" to="/profile" onClick={() => setMenuOpen(false)}>
                                        <Icon name="user" className="w-4 h-4 mr-2" />
                                        Profile
                                    </Link>
                                    <button type="button" className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" onClick={onLogout}>
                                        <Icon name="arrow" className="w-4 h-4 mr-2" />
                                        Logout
                                    </button>
                                </>
                            ) : (
                                <>
                                    <Link className="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors dark:text-slate-300 dark:hover:text-white" to="/login" onClick={() => setMenuOpen(false)}>
                                        <Icon name="user" className="w-4 h-4 mr-2" />
                                        Login
                                    </Link>
                                    <Link className="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors" to="/register" onClick={() => setMenuOpen(false)}>
                                        <Icon name="spark" className="w-4 h-4 mr-2" />
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>

                        {/* Mobile menu button */}
                        <button
                            type="button"
                            className="lg:hidden p-2 text-gray-600 hover:text-gray-900 transition-colors dark:text-slate-300 dark:hover:text-white"
                            onClick={() => setMenuOpen((current) => !current)}
                            aria-expanded={menuOpen}
                            aria-label="Toggle navigation"
                        >
                            <div className="w-6 h-5 relative">
                                <span className={`absolute h-0.5 w-6 bg-current transition-all ${menuOpen ? 'top-2 rotate-45' : 'top-0'}`} />
                                <span className={`absolute h-0.5 w-6 bg-current top-2 transition-all ${menuOpen ? 'opacity-0' : 'opacity-100'}`} />
                                <span className={`absolute h-0.5 w-6 bg-current transition-all ${menuOpen ? 'top-2 -rotate-45' : 'top-4'}`} />
                            </div>
                        </button>
                    </div>

                    {/* Mobile Navigation */}
                    {menuOpen && (
                        <div className="lg:hidden py-4 border-t border-gray-200 dark:border-slate-800">
                            <nav className="space-y-2">
                                {navigation.map((item) => (
                                    <NavLink
                                        key={item.to}
                                        to={item.to}
                                        onClick={() => setMenuOpen(false)}
                                        className={({ isActive }) =>
                                            isActive
                                                ? 'block px-4 py-2 text-blue-600 font-medium bg-blue-50 rounded-lg dark:bg-slate-800 dark:text-cyan-400'
                                                : 'block px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900'
                                        }
                                    >
                                        {item.label}
                                    </NavLink>
                                ))}
                            </nav>
                            <div className="mt-6 pt-6 border-t border-gray-200 space-y-2 dark:border-slate-800">
                                {isPublicPage && (
                                    <button
                                        type="button"
                                        className="flex items-center w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                        onClick={() => setTheme((current) => (current === 'dark' ? 'light' : 'dark'))}
                                    >
                                        <Icon name={isDarkTheme ? 'sun' : 'moon'} className="mr-2 h-4 w-4" />
                                        {isDarkTheme ? 'Mode terang' : 'Mode gelap'}
                                    </button>
                                )}
                                {isAdmin && (
                                    <Link className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" to="/admin/dashboard" onClick={() => setMenuOpen(false)}>
                                        <Icon name="shield" className="w-4 h-4 mr-2" />
                                        Admin
                                    </Link>
                                )}
                                {isCompany && (
                                    <Link className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" to="/company/dashboard" onClick={() => setMenuOpen(false)}>
                                        <Icon name="building" className="w-4 h-4 mr-2" />
                                        Company
                                    </Link>
                                )}
                                {isApplicant && (
                                    <Link className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" to="/dashboard" onClick={() => setMenuOpen(false)}>
                                        <Icon name="user" className="w-4 h-4 mr-2" />
                                        Dashboard
                                    </Link>
                                )}
                                {user ? (
                                    <>
                                        <div className="px-4 py-2">
                                            <div className="flex items-center space-x-2">
                                                <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center dark:bg-slate-700">
                                                    <Icon name="user" className="w-4 h-4 text-gray-600" />
                                                </div>
                                                <span className="text-sm font-medium text-gray-700 dark:text-slate-200">{user.name}</span>
                                            </div>
                                        </div>
                                        <Link className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" to="/profile" onClick={() => setMenuOpen(false)}>
                                            <Icon name="user" className="w-4 h-4 mr-2" />
                                            Profile
                                        </Link>
                                        <button type="button" className="flex items-center w-full px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" onClick={onLogout}>
                                            <Icon name="arrow" className="w-4 h-4 mr-2" />
                                            Logout
                                        </button>
                                    </>
                                ) : (
                                    <>
                                        <Link className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium rounded-lg transition-colors dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-900" to="/login" onClick={() => setMenuOpen(false)}>
                                            <Icon name="user" className="w-4 h-4 mr-2" />
                                            Login
                                        </Link>
                                        <Link className="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors" to="/register" onClick={() => setMenuOpen(false)}>
                                            <Icon name="spark" className="w-4 h-4 mr-2" />
                                            Register
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </header>
            {/* Workspace Strip */}
            {workspaceLinks.length > 0 && (
                <div className="border-b border-slate-200 bg-[#eef5fb] text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center space-x-8 py-3 overflow-x-auto">
                            <span className="flex-shrink-0 rounded-full bg-white px-3 py-1 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-700">Workspace</span>
                            <div className="flex space-x-6">
                                {workspaceLinks.map((item) => (
                                    <NavLink
                                        key={item.to}
                                        to={item.to}
                                        className={({ isActive }) =>
                                            isActive
                                                ? 'rounded-full bg-blue-600 px-3 py-1 text-sm font-medium text-white'
                                                : 'text-slate-600 hover:text-slate-900 font-medium text-sm transition-colors'
                                        }
                                    >
                                        {item.label}
                                    </NavLink>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            )}
            
            <main className="flex-1">{children}</main>
            
            {/* Footer */}
            <footer className="border-t border-slate-200 bg-white text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 className="mb-4 text-xl font-bold text-slate-900 dark:text-white">CirebonKita</h3>
                            <p className="mb-4 max-w-xl text-slate-600 dark:text-slate-400">
                                Tempat untuk mengikuti kabar daerah, menjelajahi peluang kerja, dan mengenal perusahaan lebih dekat.
                            </p>
                            <div className="flex space-x-4">
                                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                                    <span className="text-white font-bold">CK</span>
                                </div>
                            </div>
                        </div>
                        <div className="text-right">
                            <p className="mb-2 text-sm text-slate-500 dark:text-slate-400">Portal informasi dan karier</p>
                            <p className="text-sm text-slate-400 dark:text-slate-500">© 2026 Cirebon Kita</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
