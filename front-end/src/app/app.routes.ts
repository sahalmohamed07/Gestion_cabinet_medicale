import { Routes } from '@angular/router';

import { Home } from './features/public/home/home';
import { Login } from './features/auth/login/login';
import { Register } from './features/auth/register/register';
import { PrendreRdv } from './features/patient/prendre-rdv/prendre-rdv';

import { MedecinDashboard } from './features/medecin/dashboard/dashboard';
import { AdminDashboard } from './features/admin/dashboard/dashboard';
import { RendezvousPlanning } from './features/admin/rendezvous-planning/rendezvous-planning';
import { AdminLayout } from './features/admin/layout/admin-layout/admin-layout';

export const routes: Routes = [
  { path: '', component: Home },
  { path: 'login', component: Login },
  { path: 'register', component: Register },
  { path: 'patient/rendezvous/nouveau', component: PrendreRdv },
  { path: 'medecin/dashboard', component: MedecinDashboard },

  //  TOUT l’admin passe par le layout
  {
    path: 'admin',
    component: AdminLayout,
    children: [
      { path: 'dashboard', component: AdminDashboard },
      { path: 'rendezvous', component: RendezvousPlanning },
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
    ]
  }
];
