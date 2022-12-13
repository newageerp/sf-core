import React from "react";
import { TemplatesLoader, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { Popup } from "@newageerp/ui.ui-bundle";
import { NaePopupProvider } from "../old-ui/OldPopupProvider";

interface Props {
  schema: string;
  type: string;
  id: string;
}

export default function EditContentPopup(props: Props) {
  const { data: tData } = useTemplatesLoader();

  return (
    <NaePopupProvider isPopup={true} onClose={tData.onBack}>
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
    </NaePopupProvider>
  );
}
