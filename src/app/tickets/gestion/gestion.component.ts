import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { Ticket } from './../ticket.model';
import { TicketsService } from './../tickets.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-gestion',
  templateUrl: './gestion.component.html',
  styleUrls: ['./gestion.component.scss']
})
export class GestionComponent implements OnInit {

  tickets: Ticket[] = [];
  tickets$: Observable<Ticket[]>;
  filtro: {agente: string, estados: string, ini:number, fin: number};

  constructor(
    private ticketsService: TicketsService,
    private rutaActiva: ActivatedRoute
    ) {  }

  ngOnInit(): void {
    this.filtro = {
      agente: this.rutaActiva.snapshot.params.agente,
      estados: this.rutaActiva.snapshot.params.estados,
      ini: this.rutaActiva.snapshot.params.ini,
      fin: this.rutaActiva.snapshot.params.fin
    };
    this.tickets$ = this.ticketsService.getTickets(this.filtro.agente, this.filtro.estados, this.filtro.ini, this.filtro.fin);
    this.tickets$.subscribe(tickets => this.tickets = tickets);    
  }

}
