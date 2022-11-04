import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import React, { Fragment } from 'react'
import { useRecoilValue } from 'recoil'
import { filterScopes, INaeWidget, WidgetType } from '../utils'
import { useUIBuilder } from './builder/OldUIBuilderProvider'

export interface WidgetProps {
  type: WidgetType
  schema?: string
  element: any
  saveError?: any
  userState?: any
  extraOptions?: any
}

export interface ContentWidgetProps {
  schema: string
  element: any
  options: any
  userState?: any
  type?: WidgetType
  saveError?: any
}

export default function OldNeWidgets(props: WidgetProps) {
  const defaults = useUIBuilder();

  const userState: any = useRecoilValue<any>(OpenApi.naeUserState);
  const { type, schema, element } = props
  const allWidgets: INaeWidget[] = defaults.getTransformedWidgets();

  // console.log('ELDEBUG NEWidgets', {element});

  const widgets = allWidgets.filter((w: INaeWidget) => {
    return (
      w.type === type &&
      (w.schema === schema ||
        (w.schema === 'all' &&
          allWidgets.filter(
            (wo: INaeWidget) => wo.schema === schema && wo.comp === w.comp
          ).length === 0))
    )
  })

  widgets.sort((a: INaeWidget, b: INaeWidget) => {
    if (a.sort < b.sort) {
      return -1
    }
    if (a.sort > b.sort) {
      return 1
    }
    return 0
  })

  return (
    <Fragment>
      {widgets
        .filter((widget: INaeWidget) =>
          filterScopes(element, userState, widget)
        )
        .map((widget: INaeWidget, widgetIndex: number) => {
          const WidgetComp: any = widget.comp

          return (
            <Fragment key={'widget-' + widgetIndex}>
              <WidgetComp
                element={element}
                schema={schema}
                options={widget.options}
                userState={userState}
                saveError={props.saveError}
                type={props.type}
                extraOptions={props.extraOptions}
              />
            </Fragment>
          )
        })}
    </Fragment>
  )
}
