import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../core/auth/auth';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.html',
  styleUrls: ['./login.css'],
})
export class LoginComponent {
  email = '';
  password = '';
  loading = false;
  error: string | null = null;

  constructor(private auth: AuthService, private router: Router) {}

  onSubmit() {
    this.error = null;
    this.loading = true;

    this.auth.login(this.email, this.password).subscribe({
      next: (res) => {
        this.loading = false;
        const role = res.user.role;

        if (role === 'admin') {
          this.router.navigate(['/admin']);
        } else if (role === 'medecin') {
          this.router.navigate(['/medecin']);
        } else {
          this.router.navigate(['/patient']);
        }
      },
      error: (err) => {
        this.loading = false;
        if (err.status === 401) {
          this.error = 'Email ou mot de passe incorrect.';
        } else {
          this.error = 'Erreur de connexion, réessayez.';
        }
      },
    });
  }
}
