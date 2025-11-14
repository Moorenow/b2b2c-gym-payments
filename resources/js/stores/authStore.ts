import axios from 'axios';
import { defineStore } from 'pinia';

interface Role {
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
}

interface AuthState {
    user: User | null;
    token: string | null;
    initialized: boolean;
}

const TOKEN_KEY = 'spa-token';

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        user: null,
        token: localStorage.getItem(TOKEN_KEY),
        initialized: false,
    }),
    getters: {
        isAuthenticated: (state) => Boolean(state.token && state.user),
        displayName: (state) => state.user?.name ?? '',
        hasRole: (state) => (role: string) => state.user?.roles?.some((r) => r.name === role) ?? false,
    },
    actions: {
        setToken(token: string | null) {
            this.token = token;

            if (token) {
                axios.defaults.headers.common.Authorization = `Bearer ${token}`;
                localStorage.setItem(TOKEN_KEY, token);
            } else {
                delete axios.defaults.headers.common.Authorization;
                localStorage.removeItem(TOKEN_KEY);
            }
        },
        async bootstrap() {
            if (this.token) {
                axios.defaults.headers.common.Authorization = `Bearer ${this.token}`;
                try {
                    const { data } = await axios.get<User>('/api/user');
                    this.user = data;
                } catch {
                    this.user = null;
                    this.setToken(null);
                }
            }

            this.initialized = true;
        },
        async register(payload: { name: string; email: string; password: string; password_confirmation: string }) {
            const { data } = await axios.post<{ user: User; token: string }>('/api/register', payload);
            this.user = data.user;
            this.setToken(data.token);
        },
        async login(payload: { email: string; password: string }) {
            const { data } = await axios.post<{ user: User; token: string }>('/api/login', payload);
            this.user = data.user;
            this.setToken(data.token);
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } finally {
                this.user = null;
                this.setToken(null);
            }
        },
    },
});
