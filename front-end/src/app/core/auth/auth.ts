import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, tap } from 'rxjs';

export interface User {
  id: number;
  nom: string;
  prenom: string;
  email: string;
  role: 'admin' | 'medecin' | 'patient';
}

interface LoginResponse {
  user: User;
  token: string;
}

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private http = inject(HttpClient);
  private apiUrl = 'http://127.0.0.1:8000/api';

  private currentUserSubject = new BehaviorSubject<User | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();

  constructor() {
    const savedUser = localStorage.getItem('af_user');
    const savedToken = localStorage.getItem('af_token');

    if (savedUser && savedToken) {
      this.currentUserSubject.next(JSON.parse(savedUser));
    }
  }

  login(email: string, password: string) {
    return this.http
      .post<LoginResponse>(`${this.apiUrl}/login`, { email, password })
      .pipe(
        tap((res) => {
          localStorage.setItem('af_token', res.token);
          localStorage.setItem('af_user', JSON.stringify(res.user));
          this.currentUserSubject.next(res.user);
        })
      );
  }

  register(payload: {
  nom: string;
  prenom: string;
  email: string;
  password: string;
  date_naissance: string;
  sexe: 'M' | 'F';
  telephone?: string;
  adresse?: string;
  historique_medical?: string;
}) {
  return this.http.post<any>(`${this.apiUrl}/register`, payload).pipe(
    tap((res) => {
      // auto-login après inscription (ton backend renvoie token + user)
      if (res?.token && res?.user) {
        localStorage.setItem('af_token', res.token);
        localStorage.setItem('af_user', JSON.stringify(res.user));
        this.currentUserSubject.next(res.user);
      }
    })
  );
}


  logout() {
    localStorage.removeItem('af_token');
    localStorage.removeItem('af_user');
    this.currentUserSubject.next(null);
  }

  getToken(): string | null {
    return localStorage.getItem('af_token');
  }

  getCurrentUser(): User | null {
    return this.currentUserSubject.value;
  }

  isLoggedIn(): boolean {
    return !!this.getToken();
  }

  getRole(): User['role'] | null {
    return this.currentUserSubject.value?.role ?? null;
  }
}
