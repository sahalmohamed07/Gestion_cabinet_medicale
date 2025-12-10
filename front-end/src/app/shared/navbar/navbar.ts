import { Component, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Router, RouterLink } from "@angular/router";
import { AuthService } from "../../core/auth/auth";

@Component({
  selector: "app-navbar",
  standalone: true,
  imports: [CommonModule, RouterLink],
  template: `
    <nav style="width:100%;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.06);padding:10px 18px;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:12px;">
        <a routerLink="/" style="font-weight:700;color:#0F172A;text-decoration:none;">Cabinet Al-Firdaws</a>
      </div>

      <div style="display:flex;align-items:center;gap:12px;font-size:14px;">
        <ng-container *ngIf="(auth.currentUser$ | async) as user; else anon">
          <a *ngIf="user.role === 'patient'" routerLink="/patient/rendezvous/nouveau">Prendre RDV</a>
          <a *ngIf="user.role === 'medecin'" routerLink="/medecin/dashboard">Dashboard</a>
          <a *ngIf="user.role === 'admin'" routerLink="/admin/rendezvous">Rendez-vous (admin)</a>
          <span style="margin-left:12px;color:#333;">{{ user.prenom }} {{ user.nom }}</span>
          <button (click)="logout()" style="margin-left:8px;padding:6px 10px;border-radius:6px;border:1px solid #ddd;background:#fff;cursor:pointer;">Logout</button>
        </ng-container>
        <ng-template #anon>
          <a routerLink="/login">Connexion</a>
          <a routerLink="/register">Inscription</a>
        </ng-template>
      </div>
    </nav>
  `,
  styles: []
})
export class Navbar {
  auth = inject(AuthService);
  private router = inject(Router);

  logout() {
    this.auth.logout();
    this.router.navigate(['/login']);
  }
}
