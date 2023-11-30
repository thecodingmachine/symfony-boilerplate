export type Violations<T> = {
  [K in keyof T]?: T[K] extends Array<any>
    ? Array<Violations<T[K][number]>>
    : T[K] extends object
      ? Violations<T[K]>
      : string;
};
