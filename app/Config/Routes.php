<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rota padrão
$routes->get('/', 'Home::index');

// Rotas para autenticação de usuários
$routes->post('/api/register', 'UserController::register'); // Registro de usuário
$routes->post('/api/login', 'UserController::login');       // Login de usuário
$routes->get('/api/logout', 'UserController::logout');      // Logout de usuário

// Rotas para geração e download de PDFs
$routes->post('/api/generate-pdf', 'PdfController::generate'); // Geração de PDF
$routes->get('/api/download-pdf/(:num)', 'PdfController::download/$1'); // Download de PDF com ID específico
