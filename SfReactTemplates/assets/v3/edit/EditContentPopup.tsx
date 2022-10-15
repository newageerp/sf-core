import React from "react";
import TemplateLoader, { useTemplateLoader } from "../templates/TemplateLoader";
import { Popup } from "@newageerp/ui.popups.base.popup";
import { UI } from "@newageerp/nae-react-ui";

interface Props {
  schema: string;
  type: string;
  id: string;
}

export default function EditContentPopup(props: Props) {
  const { data: tData } = useTemplateLoader();

  return (
    <UI.Popup.NaePopupProvider isPopup={true} onClose={tData.onBack}>
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
    </UI.Popup.NaePopupProvider>
  );
}
