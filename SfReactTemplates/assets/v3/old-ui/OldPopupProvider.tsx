import React, { useContext } from "react";
import { NaeWindowProvider } from "./OldNaeWindowProvider";

export interface PopupProviderValue {
  isPopup: boolean;
  onClose?: () => void;
}

export const NaePopupContext = React.createContext<PopupProviderValue>({
  isPopup: false,
  onClose: () => {},
});

export const useNaePopup = () => useContext(NaePopupContext);

export interface PopupProps {
  children: any;
  isPopup?: boolean;
  onClose?: () => void;
}

export const NaePopupProvider = ({ children, isPopup, onClose }: PopupProps) => {
  return (
    <NaePopupContext.Provider value={{ isPopup: !!isPopup, onClose: onClose }}>
      <NaeWindowProvider>{children}</NaeWindowProvider>
    </NaePopupContext.Provider>
  );
};
