import React, { useState } from "react";
import { useHistory, useParams } from "react-router-dom";
import TemplateLoader from "../templates/TemplateLoader";

interface ParamTypes {
  schema: string;
  type: string;
  id: string;
}

interface Props {
  isPopup?: boolean,

  schema?: string,
  type?: string,
  id?: string,

  onBack?: () => void,
}

export default function MainView(props: Props) {
  const [reloadKey, setReloadKey] = useState(0);
  const history = useHistory();
  const routeParams = useParams<ParamTypes>();

  const commonProps = { ...routeParams, ...props };

  return <TemplateLoader
    key={`${commonProps.schema}-${commonProps.type}-${commonProps.id}-${reloadKey}`}
    templateName="PageMainView"
    data={commonProps}
    templateData={{
      onBack: () => {
        props.onBack ? props.onBack() : history.goBack()
      },
      onElementScopeChange: () => {
        setReloadKey(new Date().getTime());
      }
    }}
  />;
}
