import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkonComponent } from './workon.component';

describe('WorkonComponent', () => {
  let component: WorkonComponent;
  let fixture: ComponentFixture<WorkonComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WorkonComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkonComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
