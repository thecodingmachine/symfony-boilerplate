export interface BasicError {
  message: string;
  name?: string;
  detail?: string;
  error?: string;
  title?: string;
  status?: number;
  type?: string;
  violations: Array<{
    propertyPath: string;
    title: string;
  }>;
}
