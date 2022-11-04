import React, { useContext, useState, useEffect } from "react";

export interface MenuToggleValue {
  lastActiveId: string | null,
  setLastActiveId: (val: string) => void,
  allowMultipleOpen: boolean,
}

export const NaeMenuToggleContext = React.createContext<MenuToggleValue>({
  lastActiveId: null,
  setLastActiveId: (val: string) => { console.log('setLastActiveId', val) },
  allowMultipleOpen: true,
});

export const useNaeMenuToggle = () => useContext(NaeMenuToggleContext);

export interface PopupProps {
  children: any;
  allowMultipleOpen: boolean
}

export const OldMenuToggleProvider = ({ children, allowMultipleOpen }: PopupProps) => {
  const [lastActiveId, setLastActiveId] = useState<string | null>(localStorage.getItem('lastActiveId'));

  useEffect(() => {
    if (typeof lastActiveId === 'string') {
      localStorage.setItem('lastActiveId', lastActiveId);
    }
  }, [lastActiveId]);

  return (
    <NaeMenuToggleContext.Provider value={{
      lastActiveId,
      allowMultipleOpen,
      setLastActiveId
    }}>
      {children}
    </NaeMenuToggleContext.Provider>
  );
};
