import React from "react";
import TemplateLoader, { useTemplateLoader } from "../templates/TemplateLoader";
import { Popup } from "@newageerp/ui.popups.base.popup";
import { NaePopupProvider } from "../old-ui/OldPopupProvider";

interface Props {
  schema: string;
  type: string;
  id: string;
}

export default function EditContentPopup(props: Props) {
  const { data: tData } = useTemplateLoader();

  return (
    <NaePopupProvider isPopup={true} onClose={tData.onBack}>
      <Popup onClose={tData.onBack}>
        <TemplateLoader
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
