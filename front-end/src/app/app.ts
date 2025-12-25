import { Component, inject } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { Navbar } from './shared/navbar/navbar';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, Navbar],
  templateUrl: './app.html'
})
export class App {
  private router = inject(Router);

  get hideNavbar(): boolean {
    const url = this.router.url;

    return (
      url.startsWith('/login') ||
      url.startsWith('/register') ||
      url.startsWith('/admin')
    );
  }
}
