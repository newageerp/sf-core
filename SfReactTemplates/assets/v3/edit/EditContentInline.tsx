import React from "react";
import TemplateLoader from "../templates/TemplateLoader";

interface Props {
  schema: string;
  type: string;
  id: string;
  isCompact?: boolean;

  onBack?: () => void;
}

export default function EditContentInline(props: Props) {
  return (
    <TemplateLoader
      key={`${props.schema}-${props.type}-${props.id}`}
      templateName="PageMainEdit"
      data={{ ...props }}
      templateData={{
        onBack: props.onBack,
        onSaveCallback: () => {
          
        }
      }}
    />
  );
}
