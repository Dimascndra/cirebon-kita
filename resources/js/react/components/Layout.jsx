import React, { useMemo, useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
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
    const isAdmin = hasRole(user, ['SuperAdmin', 'Admin']);
    const isCompany = hasRole(user, ['Company']);
    const isApplicant = user && !isAdmin && !isCompany;
    const [menuOpen, setMenuOpen] = useState(false);

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

    return (
        <div className="app-shell">
            <div className="app-shell__backdrop app-shell__backdrop--one" />
            <div className="app-shell__backdrop app-shell__backdrop--two" />
            <header className="app-shell__header">
                <div className="app-shell__container app-shell__header-inner">
                    <Link className="app-shell__brand" to="/" onClick={() => setMenuOpen(false)}>
                        <span className="app-shell__brand-mark">CK</span>
                        <span className="app-shell__brand-copy">
                            <strong>CirebonKita</strong>
                            <small>Portal karier dan berita regional</small>
                        </span>
                    </Link>
                    <button
                        type="button"
                        className="app-shell__menu-toggle"
                        onClick={() => setMenuOpen((current) => !current)}
                        aria-expanded={menuOpen}
                        aria-label="Toggle navigation"
                    >
                        <span />
                        <span />
                    </button>
                    <div className={menuOpen ? 'app-shell__menu app-shell__menu--open' : 'app-shell__menu'}>
                        <nav className="app-shell__nav">
                            {navigation.map((item) => (
                                <NavLink
                                    key={item.to}
                                    to={item.to}
                                    onClick={() => setMenuOpen(false)}
                                    className={({ isActive }) =>
                                        isActive ? 'app-shell__nav-link app-shell__nav-link--active' : 'app-shell__nav-link'
                                    }
                                >
                                    {item.label}
                                </NavLink>
                            ))}
                        </nav>
                        <div className="app-shell__actions">
                            {isAdmin && (
                                <Link className="app-shell__button app-shell__button--ghost" to="/admin/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="shield" />
                                    Admin
                                </Link>
                            )}
                            {isCompany && (
                                <Link className="app-shell__button app-shell__button--ghost" to="/company/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="building" />
                                    Company
                                </Link>
                            )}
                            {isApplicant && (
                                <Link className="app-shell__button app-shell__button--ghost" to="/dashboard" onClick={() => setMenuOpen(false)}>
                                    <Icon name="user" />
                                    Dashboard
                                </Link>
                            )}
                            {user ? (
                                <>
                                    <span className="app-shell__user">{user.name}</span>
                                    <Link className="app-shell__button app-shell__button--ghost" to="/profile" onClick={() => setMenuOpen(false)}>
                                        <Icon name="user" />
                                        Profile
                                    </Link>
                                    <button type="button" className="app-shell__button app-shell__button--ghost" onClick={onLogout}>
                                        <Icon name="arrow" />
                                        Logout
                                    </button>
                                </>
                            ) : (
                                <>
                                    <Link className="app-shell__button app-shell__button--ghost" to="/login" onClick={() => setMenuOpen(false)}>
                                        <Icon name="user" />
                                        Login
                                    </Link>
                                    <Link className="app-shell__button app-shell__button--primary" to="/register" onClick={() => setMenuOpen(false)}>
                                        <Icon name="spark" />
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </header>
            {workspaceLinks.length > 0 && (
                <div className="workspace-strip">
                    <div className="app-shell__container workspace-strip__inner">
                        <span className="workspace-strip__label">Workspace</span>
                        <div className="workspace-strip__links">
                            {workspaceLinks.map((item) => (
                                <NavLink
                                    key={item.to}
                                    to={item.to}
                                    className={({ isActive }) =>
                                        isActive ? 'workspace-strip__link workspace-strip__link--active' : 'workspace-strip__link'
                                    }
                                >
                                    {item.label}
                                </NavLink>
                            ))}
                        </div>
                    </div>
                </div>
            )}
            <main className="app-shell__main">{children}</main>
            <footer className="app-shell__footer">
                <div className="app-shell__container app-shell__footer-inner">
                    <div>
                        <p className="app-shell__footer-title">CirebonKita</p>
                        <p>Tempat untuk mengikuti kabar daerah, menjelajahi peluang kerja, dan mengenal perusahaan lebih dekat.</p>
                    </div>
                    <div className="app-shell__footer-meta">
                        <p>Portal informasi dan karier</p>
                        <p>2026 Cirebon Kita</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
