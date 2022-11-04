import React, { useContext } from "react";


export interface SchemaProviderValue {
  schema: string,
}

export const SchemaContext = React.createContext<SchemaProviderValue>({
  schema: '',
});

export const useNaeSchema = () => useContext(SchemaContext);

export interface SchemaProps {
  children: any;
  schema?: string;
}

export const SchemaProvider = ({ children, schema }: SchemaProps) => {

  return (
    <SchemaContext.Provider value={{ schema: schema ? schema : "" }}>
      {children}
    </SchemaContext.Provider>
  );
};
