import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './dashboard.html',
  styleUrl: './dashboard.css',
})
export class AdminDashboard {
  stats = {
    patients: 230,
    medecins: 8,
    rendezvousToday: 18,
    enAttente: 5
  };

  derniersRdv = [
    {
      heure: '08:30',
      patient: 'Hassan Mohamed',
      medecin: 'Dr Amina',
      motif: 'Consultation générale',
      statut: 'confirmé'
    },
    {
      heure: '09:15',
      patient: 'Fatouma Ali',
      medecin: 'Dr Omar',
      motif: 'Suivi tension',
      statut: 'en_attente'
    }
  ];
}
