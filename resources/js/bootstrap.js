import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

const fallbackBaseUrl = 'http://localhost:8000';
const envBaseUrl = import.meta.env.VITE_API_URL;
const metaBaseUrl = document.head.querySelector('meta[name="api-base-url"]')?.getAttribute('content');
const sameOriginBaseUrl = window.location.origin;

const apiBaseUrl = envBaseUrl ?? metaBaseUrl ?? sameOriginBaseUrl ?? fallbackBaseUrl;

window.axios.defaults.baseURL = apiBaseUrl.replace(/\/+$/, '');
window.axios.defaults.withCredentials = true;
