export const buildQsDictionary = (qsFields: any[], qs: string) => {
  let _or: any = qsFields.map((s: any) => {
    if (typeof s === "string") {
      return [s, "contains", qs, true];
    }
    return [s.key, s.method ? s.method : "contains", qs, s.directSelect];
  });
  return { or: _or, _: qs };
};
