import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
//import { Ticket } from './../ticket.model';
import { TicketsService } from './../tickets.service';
//import { map, reduce } from 'rxjs/operators';

@Component({
  selector: 'app-workon',
  templateUrl: './workon.component.html',
  styleUrls: ['./workon.component.scss']
})
export class WorkonComponent implements OnInit {
  
  kanban: any = [];
  kanban$: Observable<any[]>;
  total: any = [];
  

  constructor(private ticketsService: TicketsService) { }

  ngOnInit(): void {
    this.kanban$ = this.ticketsService.getKanban();
    this.kanban$.subscribe(kanban => this.kanban = kanban);      
  }

  ngAfterViewChecked() {
      let totales = this.kanban.map(function (kan: { backlog: any; }) {
          return kan.backlog;  
      });
      this.total[0] = totales.reduce(function (acum: any=0, valor: any){
        return parseInt(acum)+parseInt(valor);
      });
      totales = this.kanban.map(function (kan: { todo: any; }) {
        return kan.todo;  
      });
      this.total[1] = totales.reduce(function (acum: any=0, valor: any){
        return parseInt(acum)+parseInt(valor);
      });
      totales = this.kanban.map(function (kan: { delivered: any; }) {
        return kan.delivered;  
      });
      this.total[2] = totales.reduce(function (acum: any, valor: any){
        return parseInt(acum)+parseInt(valor);
      });
      totales = this.kanban.map(function (kan: { fin: any; }) {
        return kan.fin;  
      });
      this.total[3] = totales.reduce(function (acum: any, valor: any){
        return parseInt(acum)+parseInt(valor);
      });
  }
}
