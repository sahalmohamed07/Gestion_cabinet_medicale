import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent implements OnInit {
  email = '';
  password = '';
  loading = false;
  error: string | null = null;

  constructor() {}

  ngOnInit(): void {}

  submit(): void {
    this.loading = true;
    this.error = null;
    // TODO: appeler AuthService ici
    setTimeout(() => {
      this.loading = false;
      // this.router.navigateByUrl('/home') par exemple
    }, 600);
  }
}