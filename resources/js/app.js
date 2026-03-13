import './bootstrap';
import './react/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './react/App';

const reactAppElement = document.getElementById('react-app');

if (reactAppElement) {
    const root = createRoot(reactAppElement);
    root.render(
        React.createElement(BrowserRouter, null, React.createElement(App))
    );
}
