import { Routes } from '@angular/router';

import { Home } from './features/public/home/home';
import { LoginComponent } from './features/auth/login/login';
import { Register } from './features/auth/register/register';
import { PrendreRdv } from './features/patient/prendre-rdv/prendre-rdv';
import { Dashboard } from './features/medecin/dashboard/dashboard';
import { RendezvousPlanning } from './features/admin/rendezvous-planning/rendezvous-planning';

export const routes: Routes = [
  { path: '', component: Home },                            // Accueil
  { path: 'login', component: LoginComponent },             // Connexion
  { path: 'register', component: Register },               // Inscription patient
  { path: 'patient/rendezvous/nouveau', component: PrendreRdv },
  { path: 'medecin/dashboard', component: Dashboard },
  { path: 'admin/rendezvous', component: RendezvousPlanning },
];
