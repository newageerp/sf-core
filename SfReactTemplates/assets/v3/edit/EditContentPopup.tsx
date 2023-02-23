import React from "react";
import { TemplatesLoader, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { Popup } from "@newageerp/ui.ui-bundle";
import { PopupProvider } from "@newageerp/v3.bundles.popup-bundle";

interface Props {
  schema: string;
  type: string;
  id: string;
}

export default function EditContentPopup(props: Props) {
  const { data: tData } = useTemplatesLoader();

  return (
    <PopupProvider isPopup={true} onClose={tData.onBack}>
      <Popup onClose={tData.onBack}>
        <TemplatesLoader
          key={`${props.schema}-${props.type}-${props.id}`}
          templateName="PageMainEdit"
          data={{ ...props, isPopup: true }}
          templateData={{
            onBack: tData.onBack,
            
          }}
        />
      </Popup>
    </PopupProvider>
  );
}
