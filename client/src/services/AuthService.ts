import type { User } from '../types/User';
import { BaseService } from './BaseService';

export class AuthService extends BaseService {
  constructor() {
    super("auth");
  }

  login(credentials: { username: string; password: string }) {
    // encrypt password
    const creds = {
      username: credentials.username,
      password: btoa(credentials.password)
    };
    return this.post<User>(`login`, creds);
  }

  logout() {
    return this.post<User>(`logout`, {});
  }

  register(user: User) {
    // encrypt password
    const credentials = {
      username: user.username,
      email: user.email,
      password: btoa(user.password),
    };
    return this.post<User>(`register`, credentials);
  }
}