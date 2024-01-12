import type { BaseEntity } from "./BaseEntity";

export type User = {
    username: string;
    email: string;
    password: string;
    token: string;
} & BaseEntity;