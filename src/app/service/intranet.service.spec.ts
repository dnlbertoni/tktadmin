import { TestBed } from '@angular/core/testing';

import { IntranetService } from './intranet.service';

describe('IntranetService', () => {
  let service: IntranetService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IntranetService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
