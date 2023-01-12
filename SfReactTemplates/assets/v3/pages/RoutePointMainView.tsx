import React, { useState } from "react";
import { useHistory, useParams } from "@newageerp/v3.templates.templates-core";
import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';

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

export default function RoutePointMainView(props: Props) {
  const [reloadKey, setReloadKey] = useState(0);
  const history = useHistory();
  const routeParams = useParams<ParamTypes>();

  const commonProps = { ...routeParams, ...props };

  return <TemplatesLoader
    key={`${commonProps.schema}-${commonProps.type}-${commonProps.id}-${reloadKey}`}
    templateName="RoutePointMainView"
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
