import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../core/auth/auth';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './login.html',
  styleUrl: './login.css',
})
export class Login {

  loginForm: FormGroup;
  isSubmitting = false;
  errorMessage = '';

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    this.errorMessage = '';

    if (this.loginForm.invalid) {
      this.errorMessage = 'Veuillez remplir correctement le formulaire.';
      this.loginForm.markAllAsTouched();
      return;
    }

    this.isSubmitting = true;

    const { email, password } = this.loginForm.value;

    this.authService.login(email, password).subscribe({
      next: (res) => {
        this.isSubmitting = false;

        // 🔥 AuthService stocke déjà: af_token, af_user
        // Donc pas besoin de restocker ici.

        const role = res.user.role;

        if (role === 'admin') {
          this.router.navigate(['/admin/rendezvous']);
        } else if (role === 'medecin') {
          this.router.navigate(['/medecin/dashboard']);
        } else {
          this.router.navigate(['/patient/home']);
        }
      },
      error: (err) => {
        this.isSubmitting = false;
        this.errorMessage = err.error?.message || 'Email ou mot de passe incorrect.';
      }
    });
  }
}
