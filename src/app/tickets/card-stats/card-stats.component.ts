import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { TicketsService } from './../tickets.service';



@Component({
  selector: 'app-card-stats',
  templateUrl: './card-stats.component.html',
  styleUrls: ['./card-stats.component.scss']
})
export class CardStatsComponent implements OnInit {

  kpi: any = [];
  kpi$: Observable<any[]>;
  total: any = [];

  constructor(
    private ticketsService: TicketsService
  ) { }

  ngOnInit(): void {
    this.kpi$ = this.ticketsService.getKpi_1();
    this.kpi$.subscribe(kpi => this.kpi = kpi);   
  }

}
