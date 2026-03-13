import React, { useCallback, useEffect, useState } from 'react';
import { Navigate, Route, Routes, useNavigate } from 'react-router-dom';
import Layout from './components/Layout';
import { api, getStoredUser, setAuthToken, setStoredUser } from './lib/api';
import HomePage from './pages/HomePage';
import NewsPage from './pages/NewsPage';
import NewsDetailPage from './pages/NewsDetailPage';
import JobsPage from './pages/JobsPage';
import JobDetailPage from './pages/JobDetailPage';
import CompaniesPage from './pages/CompaniesPage';
import CompanyDetailPage from './pages/CompanyDetailPage';
import AboutPage from './pages/AboutPage';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';
import DashboardPage from './pages/DashboardPage';
import ProfilePage from './pages/ProfilePage';
import ApplicationsPage from './pages/ApplicationsPage';
import ApplicationDetailPage from './pages/ApplicationDetailPage';
import CompanyDashboardPage from './pages/CompanyDashboardPage';
import CompanyApplicantsPage from './pages/CompanyApplicantsPage';
import CompanyApplicantDetailPage from './pages/CompanyApplicantDetailPage';
import AdminDashboardPage from './pages/AdminDashboardPage';
import AdminUsersPage from './pages/AdminUsersPage';
import AdminRolesPage from './pages/AdminRolesPage';
import AdminNewsPage from './pages/AdminNewsPage';
import AdminNewsFormPage from './pages/AdminNewsFormPage';
import AdminJobsPage from './pages/AdminJobsPage';
import AdminJobsFormPage from './pages/AdminJobsFormPage';
import AdminAdsPage from './pages/AdminAdsPage';
import ForgotPasswordPage from './pages/ForgotPasswordPage';
import ResetPasswordPage from './pages/ResetPasswordPage';
import VerifyEmailPage from './pages/VerifyEmailPage';
import ConfirmPasswordPage from './pages/ConfirmPasswordPage';

function hasRole(user, roleNames) {
    const names = (user?.roles || []).map((role) => role.name);
    return roleNames.some((role) => names.includes(role));
}

function ProtectedRoute({ user, roles = [], children }) {
    if (!user) {
        return <Navigate to="/login" replace />;
    }

    if (roles.length > 0 && !hasRole(user, roles)) {
        return <Navigate to="/" replace />;
    }

    return children;
}

function AppContent() {
    const navigate = useNavigate();
    const [user, setUser] = useState(getStoredUser());

    const handleAuthSuccess = useCallback(
        (payload) => {
            const token = payload?.token;
            const nextUser = payload?.user ?? null;

            setAuthToken(token ?? null);
            setStoredUser(nextUser);
            setUser(nextUser);

            if (nextUser) {
                window.location.assign('/dashboard');
                return;
            }

            navigate('/');
        },
        [navigate]
    );

    const handleLogout = useCallback(async () => {
        try {
            await api.post('/logout');
        } catch {
            // Token may already be invalid; clear local state anyway.
        }

        setAuthToken(null);
        setStoredUser(null);
        setUser(null);
        navigate('/');
    }, [navigate]);

    useEffect(() => {
        if (!user) {
            return;
        }

        let active = true;

        api.get('/me')
            .then((response) => {
                if (!active) {
                    return;
                }

                const nextUser = response.data?.data ?? null;
                setStoredUser(nextUser);
                setUser(nextUser);
            })
            .catch(() => {
                if (!active) {
                    return;
                }

                setAuthToken(null);
                setStoredUser(null);
                setUser(null);
            });

        return () => {
            active = false;
        };
    }, [user?.id]);

    return (
        <Layout user={user} onLogout={handleLogout}>
            <Routes>
                <Route path="/" element={<HomePage />} />
                <Route path="/tentang" element={<AboutPage />} />
                <Route path="/berita" element={<NewsPage />} />
                <Route path="/news/:slug" element={<NewsDetailPage />} />
                <Route path="/lowongan" element={<JobsPage />} />
                <Route path="/lowongan/:slug" element={<JobDetailPage />} />
                <Route path="/perusahaan" element={<CompaniesPage />} />
                <Route path="/perusahaan/:slug" element={<CompanyDetailPage />} />
                <Route
                    path="/dashboard"
                    element={
                        <ProtectedRoute user={user}>
                            <DashboardPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/profile"
                    element={
                        <ProtectedRoute user={user}>
                            <ProfilePage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/my-applications"
                    element={
                        <ProtectedRoute user={user}>
                            <ApplicationsPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/my-applications/:id"
                    element={
                        <ProtectedRoute user={user}>
                            <ApplicationDetailPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/company/dashboard"
                    element={
                        <ProtectedRoute user={user} roles={['Company']}>
                            <CompanyDashboardPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/company/applicants"
                    element={
                        <ProtectedRoute user={user} roles={['Company']}>
                            <CompanyApplicantsPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/company/applicants/:id"
                    element={
                        <ProtectedRoute user={user} roles={['Company']}>
                            <CompanyApplicantDetailPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/dashboard"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminDashboardPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/users"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminUsersPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/roles"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminRolesPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/news"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminNewsPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/news/create"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminNewsFormPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/news/:id"
                    element={<Navigate to="/admin/news" replace />}
                />
                <Route
                    path="/admin/news/:id/edit"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminNewsFormPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/jobs"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminJobsPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/jobs/create"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminJobsFormPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/jobs/:id"
                    element={<Navigate to="/admin/jobs" replace />}
                />
                <Route
                    path="/admin/jobs/:id/edit"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminJobsFormPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/admin/ads"
                    element={
                        <ProtectedRoute user={user} roles={['SuperAdmin', 'Admin']}>
                            <AdminAdsPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/login"
                    element={user ? <Navigate to="/dashboard" replace /> : <LoginPage onSuccess={handleAuthSuccess} />}
                />
                <Route
                    path="/register"
                    element={user ? <Navigate to="/dashboard" replace /> : <RegisterPage onSuccess={handleAuthSuccess} />}
                />
                <Route path="/forgot-password" element={<ForgotPasswordPage />} />
                <Route path="/reset-password/:token" element={<ResetPasswordPage />} />
                <Route
                    path="/verify-email"
                    element={
                        <ProtectedRoute user={user}>
                            <VerifyEmailPage />
                        </ProtectedRoute>
                    }
                />
                <Route
                    path="/confirm-password"
                    element={
                        <ProtectedRoute user={user}>
                            <ConfirmPasswordPage />
                        </ProtectedRoute>
                    }
                />
                <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
        </Layout>
    );
}

export default function App() {
    return <AppContent />;
}
