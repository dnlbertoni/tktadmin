import { Component, OnInit } from '@angular/core';
import { TicketService } from '../../../service/ticket.service';
/*import { Ticket } from '../../../interface/ticket';*/


@Component({
  selector: 'app-tickets',
  templateUrl: './tickets.component.html',
  styleUrls: ['./tickets.component.scss'],
})
export class TicketsComponent implements OnInit {

  tickets: any = [];
  tkBind:  any;
  titulo: string;

  constructor(
    private restApi : TicketService
  ) { 

  }

  ngOnInit() {
    /*this.titulo = 'Tickets de Intranet 2.0';*/
    this.restApi.getTickets().subscribe( 
      data => { 
         this.tickets = data;
        }
      );
  }

}
